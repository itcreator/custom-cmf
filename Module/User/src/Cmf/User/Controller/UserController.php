<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Controller;

use Cmf\Form\Form;
use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Controller\CrudController;
use Cmf\Controller\Response\Forward;
use Cmf\Db\BaseEntity;
use Cmf\User\Model\Entity\User;
use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class UserController extends CrudController
{
    /** @var string */
    protected $titleField = 'userName';

    /** @var string */
    protected $entityName = 'Cmf\User\Model\Entity\User';

    /**
     * @return AbstractFieldConfig
     */
    protected function getFieldsConfig()
    {
        return \Cmf\Component\Field\Factory::getConfig('Cmf\User');
    }

    /**
     * @return \Cmf\Component\ActionLink\AbstractConfig
     */
    protected function getActionLinkConfig()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\User', 'actionLink');
        $className = $config->configClass;

        return new $className();
    }

    /**
     * @param int|null $id
     * @return Form
     */
    protected function createForm($id = null)
    {
        $form = parent::createForm($id);

        if ($id) {
            $form->removeElement('password');
        }

        return $form;
    }

    /**
     * @param User|BaseEntity $entity
     * @return User
     */
    protected function fillEntityForCreate(BaseEntity $entity)
    {
        $entity->setRegistrationTime(time());

        return $this;
    }

    /**
     * View user page
     *
     * @return array|Forward
     */
    public function readAction()
    {
        $result = parent::readAction();
        if (!is_array($result)) {
            return $result;
        }

        $em = $this->getEntityManager();
        $status = $this->getFieldsConfig()->getConfigItem('status');
        $statuses = $status['dataSource']->getData();

        $entity = $em->find($this->entityName, Application::getRequest()->get('id'));
        $status = $entity->getStatus();

        return array_merge($result, ['status' => isset($statuses[$status]) ? $statuses[$status] : '']);
    }

    /**
     * @param User|BaseEntity $entity
     * @return bool
     */
    protected function delete(BaseEntity $entity)
    {
        try {
            $entity->setStatus(User::STATUS_DELETED);
            $em = $this->getEntityManager();
            $em->persist($entity);
            $em->flush();

            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}
