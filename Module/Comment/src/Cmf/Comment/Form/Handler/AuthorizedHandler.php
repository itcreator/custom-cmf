<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Form\Handler;

use Cmf\User\Auth;
use Cmf\Controller\Exception404;
use Cmf\Comment\Form\AuthorizedForm;
use Cmf\Comment\Model\Entity\Comment;
use Cmf\Form\Handler\AbstractHandler;
use Cmf\System\Application;
use Cmf\System\Request;

/**
 * Comment form handler for authorized users
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class AuthorizedHandler extends AbstractHandler
{
    protected $handlerParams = [
        'contentEntityName' => null,
        'commentEntityName' => null,
    ];

    /**
     * @param array $formParams
     * @param array $handlerParams
     * @throws Exception
     */
    public function __construct(array $formParams = [], $handlerParams = [])
    {
        parent::__construct($formParams, $handlerParams);

        if (!class_exists($this->handlerParams['contentEntityName'])) {
            throw new Exception('Parameter "contentEntityName" must be correct entity name');
        }

        if (!class_exists($this->handlerParams['commentEntityName'])) {
            throw new Exception('Parameter "commentEntityName" must be correct entity name');
        }
    }


    /**
     * @return $this
     */
    public function initForm()
    {
        $this->form = new AuthorizedForm($this->formParams);

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     * @throws \Cmf\Controller\Exception404
     */
    protected function fillEntity(Comment $comment)
    {
        $form = $this->form;

        $request = Application::getRequest();
        $em = Application::getEntityManager();
        $content = $em->find($this->handlerParams['contentEntityName'], (int)$request->get('idContent'));

        if (!$content) {
            throw new Exception404();
        }

        $comment
            ->setText($form->getElement('text')->getValue())
            ->setUser(Application::getAuth()->getUser())
            ->setCreatedFromIp($_SERVER["REMOTE_ADDR"])
            ->setContent($content);
        ;

        if (0 < $idParent = (int)$request->get('idParent')) {
            $parent = $em->find($this->handlerParams['commentEntityName'], $idParent);
            $comment->setParent($parent);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    protected function saveComment(Comment $comment)
    {
        $this->fillEntity($comment);

        $em = Application::getEntityManager();
        $em->persist($comment);
        $em->flush();

        return $this;
    }

    /**
     * @param array|null $data
     * @return bool
     */
    public function handle(array $data = null)
    {
        $data = $this->prepareFormData($data);

        $form = $this->form;
        $result = false;

        if ($form->isSubmitted()) {
            $form->setValue($data);
            if ($form->validate()) {
                $this->saveComment(new $this->handlerParams['commentEntityName']());

                $result = true;
            }
        }

        return $result;
    }

    /**
     * @return Comment
     */
    protected function loadCommentFromRequest()
    {
        $em = Application::getEntityManager();
        $request = Application::getRequest();
        /** @var Comment $comment */
        $comment = $em->getRepository($this->handlerParams['commentEntityName'])
            ->findOneBy([
                'id' => (int)$request->get('id'),
                'contentId' => (int)$request->get('idContent'),
            ]);

        return $comment;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function fillForm(Comment $comment)
    {
        $this->form->getElement('text')->setValue($comment->getText());

        return $this;
    }

    /**
     * @return bool
     */
    public function handleEdit()
    {
        $comment = $this->loadCommentFromRequest();
        $this->fillForm($comment);
        $data = Application::getRequest()->getVars(Request::TYPE_POST);

        $form = $this->form;
        $result = false;

        if ($form->isSubmitted()) {
            $form->setValue($data);
            if ($form->validate()) {
                $this->saveComment($comment);

                $result = true;
            }
        }

        return $result;
    }
}
