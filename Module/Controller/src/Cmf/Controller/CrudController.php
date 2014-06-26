<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Controller;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\ActionLink\Factory as ActionLinkFactory;
use Cmf\Component\Field\AbstractFieldConfig;

use Cmf\Controller\Response\Forward;
use Cmf\Controller\Response\PostRedirectGet;
use Cmf\Db\BaseEntity;
use Cmf\Form\Element\Submit;
use Cmf\Form\Form;
use Cmf\System\Application;
use Cmf\System\Confirmation;
use Cmf\System\Message;
use Cmf\View\Helper\HelperFactory;

/**
 * CRUD controller. (Create Read Update Delete actions)
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class CrudController extends AbstractController
{
    const ERR_DB = 'dbError';
    const ERR_NOT_DELETED = 'errNotDeleted';
    const MSG_CREATION_OK = 'creationOk';
    const MSG_EDITION_OK = 'editionOk';
    const MSG_DELETING_OK = 'deletingOk';
    const MSG_CONFIRM_DELETE = 'msgConfirmDelete';
    const TITLE_CONFIRM_DELETE = 'titleConfirmDelete';

    const ITEMS_ON_PAGE = 20;

    /** @var string */
    protected $titleField = '';

    /** @var string */
    protected $entityName = '';

    /** @var string  */
    protected $fieldConfigKey = 'field';

    /**
     * @return AbstractFieldConfig
     */
    protected function getFieldsConfig()
    {
        $mm = Application::getModuleManager();
        $moduleName = $mm->getModuleNameByClass(get_class($this));

        return \Cmf\Component\Field\Factory::getConfig($moduleName, $this->fieldConfigKey);
    }

    /**
     * @return AbstractConfig|null null - with out action links
     */
    protected function getActionLinkConfig()
    {
        $mm = Application::getModuleManager();
        $moduleName = $mm->getModuleNameByClass(get_class($this));

        $config = Application::getConfigManager()->loadForModule($moduleName, 'actionLink');

        $className = $config && $config->configClass ? $config->configClass : null;

        return $className ? new $className() : null;
    }

    /**
     * Default CRUD action.  Show list of items.
     */
    public function defaultAction()
    {
        $em = $this->getEntityManager();
        $pagerParams = ['itemsCountPerPage' => static::ITEMS_ON_PAGE, 'sort' => Application::getRequest()->getSort(),];
        $repository = $em->getRepository($this->entityName);

        $grid = new \Cmf\Component\Grid([
            'fields' => $this->getFieldsConfig(),
            'data' => $repository,
            'pager' => $pagerParams,
            'idField' => 'id',
            'actionLinks' => $this->getActionLinkConfig(),
        ]);

        return ['grid' => $grid];
    }

    /**
     * @param BaseEntity $entity
     * @param \Cmf\Form\Form $form
     * @return $this
     */
    protected function fillEntityFromForm(BaseEntity $entity, Form $form)
    {
        Application::getFormDataMapper()->fillEntityFromForm($this->getFieldsConfig(), $entity, $form);

        return $this;
    }

    /**
     * @param BaseEntity $entity
     * @return $this
     */
    protected function fillEntityForCreate(BaseEntity $entity)
    {
        return $this;
    }

    /**
     * @param BaseEntity $entity
     * @return $this
     */
    protected function fillEntityForEdit(BaseEntity $entity)
    {
        return $this;
    }

    /**
     * @param null|int $id
     * @return \Cmf\Form\Form
     */
    protected function createForm($id = null)
    {
        return \Cmf\Form\Factory::create($this->entityName, $this->getFieldsConfig(), $id);
    }

    /**
     * Create new item
     *
     * @return array|PostRedirectGet
     */
    public function createAction()
    {
        $form = $this->createForm();
        $submit = new Submit();
        $lng = \Cmf\Language\Factory::get($this);
        $submit->setValue($lng['create']);
        $form->setElement($submit);

        $response = [
            'form' => $form,
            'actionLinks' => ActionLinkFactory::createLinks($this->getActionLinkConfig()),
        ];

        if ($form->isSubmitted()) {
            $form->getValuesFromRequest();
            if ($form->validate()) {
                $entity = new $this->entityName();
                $this->fillEntityFromForm($entity, $form);
                $this->fillEntityForCreate($entity);

                try {
                    $em = $this->getEntityManager();
                    $em->persist($entity);
                    $em->flush();
                    $result = true;
                } catch (\Exception $e) {
                    $lng = \Cmf\Language\Factory::get($this);
                    $form->getMessages()->setItem(new Message($lng[self::ERR_DB]));
                    $result = false;
                }
                if ($result) {
                    $response = new PostRedirectGet($this);
                    $response->setRedirectPath([
                        'module' => $this->request->getModuleName(),
                        'controller' => $this->getControllerName(),
                        'action' => 'creationOk',
                    ]);
                }
            }
        }

        return $response;
    }

    /**
     * @param BaseEntity $entity
     * @param \Cmf\Form\Form $form
     * @return $this
     */
    public function fillFormFromEntity(BaseEntity $entity, Form $form)
    {
        Application::getFormDataMapper()->fillFormFromEntity($this->getFieldsConfig(), $entity, $form);

        return $this;
    }

    /**
     * @return array|PostRedirectGet
     */
    public function editAction()
    {
        $id = Application::getRequest()->get('id');
        if (!$id || $id != (string)(int)$id) {
            return $this->forward404();
        }

        $fields = \Cmf\Component\Field\Factory::getFieldsById($id, $this->entityName, $this->getFieldsConfig());
        if (!$fields) {
            return $this->forward404();
        }

        $entity = $fields->getItem('id')->getEntity();

        $form = $this->createForm($id);
        $this->fillFormFromEntity($entity, $form);

        $submit = new Submit();
        $lng = \Cmf\Language\Factory::get($this);
        $submit->setValue($lng['save']);
        $form->setElement($submit);

        $response = [
            'form' => $form,
            'actionLinks' => ActionLinkFactory::createLinks($this->getActionLinkConfig(), $fields['id']),
        ];

        if ($form->isSubmitted()) {
            $form->getValuesFromRequest();
            if ($form->validate()) {
                $this->fillEntityFromForm($entity, $form);
                $this->fillEntityForEdit($entity);

                try {
                    $em = $this->getEntityManager();
                    $em->persist($entity);
                    $em->flush();
                    $result = true;
                } catch (\Exception $e) {
                    $form->getMessages()->setItem(new Message($lng[self::ERR_DB]));
                    $result = false;
                }
                if ($result) {
                    $response = new PostRedirectGet($this);
                    $response->setRedirectPath([
                        'module' => $this->request->getModuleName(),
                        'controller' => $this->getControllerName(),
                        'action' => 'editionOk',
                    ]);
                }
            }
        }

        return $response;
    }

    /**
     * @return array|Forward
     */
    public function readAction()
    {
        $id = Application::getRequest()->get('id');
        if (!$id || $id != (string)(int)$id) {
            return $this->forward404();
        }

        $fields = \Cmf\Component\Field\Factory::getFieldsById($id, $this->entityName, $this->getFieldsConfig());
        if (!$fields) {
            return $this->forward404();
        }

        return [
            'fields' => $fields,
            'actionLinks' => ActionLinkFactory::createLinks($this->getActionLinkConfig(), $fields['id']),
        ];
    }

    /**
     * @param BaseEntity $entity
     * @return bool
     */
    protected function delete(BaseEntity $entity)
    {
        try {
            $em = $this->getEntityManager();
            $em->remove($entity);
            $em->flush();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param BaseEntity $entity
     * @return Confirmation
     */
    protected function prepareDeletingConfirmation(BaseEntity $entity)
    {
        $method = 'get' . $this->titleField;
        $lng = \Cmf\Language\Factory::get($this);
        $title = sprintf($lng[self::TITLE_CONFIRM_DELETE], $entity->$method());
        $message = sprintf($lng[self::MSG_CONFIRM_DELETE], $entity->$method());

        $urlNo = [
            'controller' => $this->request->getControllerName(),
            'module' => $this->request->getModuleName(),
        ];

        return new Confirmation($title, $message, $urlNo);
    }

    public function deleteAction()
    {
        $request = Application::getRequest();
        $id = $request->get('id');
        if (!$id || $id != (string)(int)$id) {
            return $this->forward404();
        }

        $fields = \Cmf\Component\Field\Factory::getFieldsById($id, $this->entityName, $this->getFieldsConfig());
        if (!$fields) {
            return $this->forward404();
        }
        $entity = $fields->getItem('id')->getEntity();

        $response = [];
        if ($request->get('start')) {
            if ($this->delete($entity)) {
                $response = new PostRedirectGet($this);
                $response->setRedirectPath([
                    'module' => $this->request->getModuleName(),
                    'controller' => $this->getControllerName(),
                    'action' => 'deletingOk',
                ]);
            } else {
                $lng = \Cmf\Language\Factory::get($this);
                $mb = HelperFactory::getMessageBox();
                $mb->addError($lng[self::ERR_NOT_DELETED]);
                $mb->addError($lng[self::ERR_DB]);
                $response = $this->forward('default');
            }
        } else {
            $response['confirmation'] = $this->prepareDeletingConfirmation($entity);
            $response['actionLinks'] = ActionLinkFactory::createLinks($this->getActionLinkConfig(), $fields['id']);
        }

        return $response;
    }

    /**
     *  Creation of a new item is successfully
     *
     * @return Forward
     */
    public function creationOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);

        HelperFactory::getMessageBox()->addMessage($lng[self::MSG_CREATION_OK]);

        return $this->forward('default');
    }

    /**
     * Edition of a item is successfully
     *
     * @return Forward
     */
    public function editionOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);
        HelperFactory::getMessageBox()->addSuccessMessage($lng[self::MSG_EDITION_OK]);

        return $this->forward('default');
    }

    /**
     *  Deleting of a item is successfully
     */
    public function deletingOkAction()
    {
        $lng = \Cmf\Language\Factory::get($this);
        HelperFactory::getMessageBox()->addMessage($lng[self::MSG_DELETING_OK]);

        return $this->forward('default');
    }
}
