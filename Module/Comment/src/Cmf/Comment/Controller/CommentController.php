<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Controller;

use Cmf\Comment\Model\Entity\Comment;
use Cmf\Comment\Form\Handler\HandlerFactory;
use Cmf\Comment\Model\Repository\CommentRepository;
use Cmf\Controller\AbstractController;
use Cmf\Controller\Response\Forward;
use Cmf\Controller\Response\PostRedirectGet;
use Cmf\System\Application;
use Cmf\System\Confirmation;
use Cmf\System\Request;
use Cmf\User\Auth;
use Cmf\View\Helper\HelperFactory;

/**
 * Controller for comments
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class CommentController extends AbstractController
{
    const ERR_DB = 'dbError';
    const ERR_NOT_DELETED = 'errNotDeleted';

    const MSG_CREATION_OK = 'creationOk';
    const MSG_EDITION_OK = 'editionOk';
    const MSG_DELETION_OK = 'deletionOk';
    const MSG_ROOT_CREATED = 'rootCreated';
    const MSG_ROOT_ALREADY_EXIST = 'rootAlreadyExist';

    const TITLE_CONFIRM_DELETE = 'titleConfirmDelete';

    /** @var string */
    protected $commentEntityName;

    /** @var string */
    protected $contentEntityName;

    /** @var null|string You must set this value. Example: "News", "Article" */
    protected $contentControllerName = null;

    /**
     * @param int $idContent
     * @param string $moduleName
     * @return \Cmf\Component\ActionLink\AbstractConfig
     */
    protected function getActionLinkConfig($idContent, $moduleName)
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\Comment', 'actionLink');
        $className = $config->configClass;

        return new $className($idContent, $moduleName);
    }

    public function createAction()
    {
        $handlerParams =  [
            'contentEntityName' => $this->contentEntityName,
            'commentEntityName' => $this->commentEntityName,
        ];

        $formHandler = HandlerFactory::create($this->request, [], $handlerParams);

        $request = Application::getRequest();
        if ($formHandler->handle($request->getVars(Request::TYPE_POST))) {
            $response = new PostRedirectGet($this);
            $response->setRedirectPath([
                'module' => $this->request->getModuleName(),
                'controller' => $this->getControllerName(),
                'action' => 'creationOk',
                'idContent' =>  (int)$request->get('idContent'),
            ]);
        } else {
            $response = [
                'commentForm' => $formHandler->getForm(),
            ];
        }

        return $response;
    }

    /**
     * @return Forward
     */
    public function creationOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);

        HelperFactory::getMessageBox()->addMessage($lng[self::MSG_CREATION_OK]);

        $request = Application::getRequest();
        $request->set('id', $request->get('idContent'), Request::TYPE_GET);

        return $this->forward('read', $this->contentControllerName);
    }

    /**
     * @return Forward
     */
    public function initrootAction()
    {
        $em = $this->getEntityManager();

        /** @var $repository \Cmf\Comment\Model\Repository\CommentRepository */
        $repository = $em->getRepository($this->commentEntityName);

        $mb = HelperFactory::getMessageBox();
        $lng = \Cmf\Language\Factory::get($this);

        if (!$repository->findOneBy(['parent' => null])) {
            /** @var $root \Cmf\Comment\Model\Entity\Comment */
            $root = new $this->commentEntityName;
            $root
                ->setText('root')
                ->setUser(Application::getAuth()->getUser())
            ;
            $em->persist($root);
            $em->flush();

            $mb->addMessage($lng[self::MSG_ROOT_CREATED]);
        } else {
            $mb->addMessage($lng[self::MSG_ROOT_ALREADY_EXIST]);
        }

        return $this->forward('default', 'Article');
    }

    public function editAction()
    {
        $handlerParams =  [
            'contentEntityName' => $this->contentEntityName,
            'commentEntityName' => $this->commentEntityName,
        ];

        $request = Application::getRequest();

        $action = Application::getUrlBuilder()->build([
            'module' => $this->request->getModuleName(),
            'controller' => 'Comment',
            'action' => 'edit',
            'idContent' => (int)$request->get('idContent'),
            'id' => (int)$request->get('id'),
        ]);

        $formHandler = HandlerFactory::create($this->request, ['action' => $action], $handlerParams);

        if ($formHandler->handleEdit()) {
            $response = new PostRedirectGet($this);
            $response->setRedirectPath([
                'module' => $this->request->getModuleName(),
                'controller' => $this->getControllerName(),
                'action' => 'editionOk',
                'idContent' =>  (int)$request->get('idContent'),
            ]);
        } else {
            $response = [
                'commentForm' => $formHandler->getForm(),
            ];
        }

        return $response;
    }

    /**
     * @return Forward
     */
    public function editionOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);
        HelperFactory::getMessageBox()->addMessage($lng[self::MSG_EDITION_OK]);

        $request = Application::getRequest();
        $request->set('id', $request->get('idContent'), Request::TYPE_GET);

        return $this->forward('read', $this->contentControllerName);
    }

    /**
     * @param \Cmf\Comment\Model\Entity\Comment $comment
     * @return bool
     */
    protected function delete(\Cmf\Comment\Model\Entity\Comment $comment)
    {
        try {
            $em = $this->getEntityManager();
            $em->remove($comment);
            $em->flush();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function deleteAction()
    {
        $em = Application::getEntityManager();
        /** @var CommentRepository $repository */
        $repository = $em->getRepository($this->commentEntityName);
        $request = Application::getRequest();
        $idContent = (int)$request->get('idContent');
        /** @var Comment $comment */
        $comment = $repository->findOneBy([
            'id' => (int)$request->get('id'),
            'contentId' => $idContent,
        ]);

        if (!$comment) {
            return $this->forward404();
        }

        $response = [];
        if (Application::getRequest()->get('start')) {
            if ($this->delete($comment)) {
                $response = new PostRedirectGet($this);
                $response->setRedirectPath([
                    'module' => $this->request->getModuleName(),
                    'controller' => $this->getControllerName(),
                    'action' => 'deletionOk',
                    'idContent' => $idContent,
                ]);
            } else {
                $lng = \Cmf\Language\Factory::get($this);
                $mb = HelperFactory::getMessageBox();
                $mb->addError($lng[self::ERR_NOT_DELETED]);
                $mb->addError($lng[self::ERR_DB]);
                $response = $this->forward('default');
            }
        } else {
            $response['confirmation'] = $this->prepareDeletingConfirmation($comment);
            $field = \Cmf\Component\Field\Factory::create([], $comment->getId());
            $actionLinkConfig = $this->getActionLinkConfig($idContent, $repository->getModuleName());
            $response['actionLinks'] = \Cmf\Component\ActionLink\Factory::createLinks($actionLinkConfig, $field);
        }

        return $response;
    }

    /**
     * @return Forward
     */
    public function deletionOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);

        HelperFactory::getMessageBox()->addMessage($lng[self::MSG_DELETION_OK]);

        $request = Application::getRequest();
        $request->set('id', $request->get('idContent'), Request::TYPE_GET);

        return $this->forward('read', $this->contentControllerName);
    }

    /**
     * @return array
     */
    protected function getRedirectToContentParams()
    {
        return [
            'controller' => $this->contentControllerName,
            'module' => $this->request->getModuleName(),
            'action' => 'read',
            'id' => (int)Application::getRequest()->get('idContent'),
        ];
    }

    /**
     * @param Comment $comment
     * @return Confirmation
     */
    protected function prepareDeletingConfirmation(Comment $comment)
    {
        $lng = \Cmf\Language\Factory::get($this);
        $title = sprintf($lng[self::TITLE_CONFIRM_DELETE]);
        $urlNo = $this->getRedirectToContentParams();

        return new Confirmation($title, $comment->getText(), $urlNo);
    }
}
