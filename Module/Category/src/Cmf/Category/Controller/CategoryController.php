<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category\Controller;

use Cmf\Category\Model\Entity\Category;
use Cmf\Category\Tree;
use Cmf\Category\Validator\NotChild;
use Cmf\Controller\CrudController;
use Cmf\Controller\Response\Forward;
use Cmf\Controller\Response\PostRedirectGet;
use Cmf\Form\Form;
use Cmf\System\Application;
use Cmf\View\Helper\HelperFactory;

/**
 * Abstract category controller
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class CategoryController extends CrudController
{
    const MSG_ROOT_CREATED = 'rootCreated';
    const MSG_ROOT_ALREADY_EXIST = 'rootAlreadyExist';

    /** @var string */
    protected $entityName = '';

    /** @var string */
    protected $titleField = 'title';

    /**
     * @return \Cmf\Component\ActionLink\AbstractConfig
     */
    protected function getActionLinkConfig()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\Category', 'actionLink');
        $className = $config->configClass;

        return new $className();
    }

    /**
     * If node not root then method return true
     * @return bool
     */
    protected function checkRootNode()
    {
        $id = (int)Application::getRequest()->get('id');

        $repository = $this->getEntityManager()->getRepository($this->entityName);
        /** @var $node Category */
        if ($node = $repository->find($id)) {
            return $node->getLevel() > 1;
        }

        return false;
    }

    /**
     * @param null|int $id
     * @return Form
     */
    protected function createForm($id = null)
    {
        $form = parent::createForm($id);
        $id = (int)Application::getRequest()->get('id');
        $parentInput = $form->getElement('parent');
        $parentInput->addValidator(new NotChild($id, $this->entityName));
        return $form;
    }

    /**
     * @return array|Forward|PostRedirectGet
     */
    public function editAction()
    {
        if ($this->checkRootNode()) {
            return parent::editAction();
        } else {
            return $this->forward404();
        }
    }

    /**
     * @return array|Forward
     */
    public function readAction()
    {
        if ($this->checkRootNode()) {
            $response =  parent::readAction();

            if (is_array($response)) {
                $urlParams = [
                    'controller' => $this->request->getControllerName(),
                    'module' => $this->request->getModuleName(),
                    'action' => 'read',
                ];
                $id = (int)Application::getRequest()->get('id');
                $response['categoryTree'] = new Tree($this->entityName, $urlParams, $id);
            }

        } else {
            $response = $this->forward404();
        }

        return $response;
    }

    /**
     * @return array|Forward|PostRedirectGet
     */
    public function deleteAction()
    {
        if ($this->checkRootNode()) {
            return parent::deleteAction();
        } else {
            return $this->forward404();
        }
    }

    /**
     * @return Forward
     */
    public function initRootAction()
    {
        $em = $this->getEntityManager();

        $repository = $em->getRepository($this->entityName);

        $mb = HelperFactory::getMessageBox();
        $lng = \Cmf\Language\Factory::get($this);

        if (!$repository->findOneBy(['parent' => null])) {
            /** @var $root Category */
            $root = new $this->entityName;
            $root->setTitle('root')
                ->setDescription('It is system root node');
            $em->persist($root);
            $em->flush();
            $mb->addMessage($lng[self::MSG_ROOT_CREATED]);
        } else {
            $mb->addMessage($lng[self::MSG_ROOT_ALREADY_EXIST]);
        }

        return $this->forward('default');
    }
}
