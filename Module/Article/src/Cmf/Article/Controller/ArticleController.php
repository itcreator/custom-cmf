<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Controller;

use Cmf\User\Auth;
use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Controller\CrudController;
use Cmf\Db\BaseEntity;
use Cmf\System\Application;

/**
 * Controller for articles
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ArticleController extends CrudController
{
    /** @var string */
    protected $titleField = 'title';

    /** @var string */
    protected $entityName = 'Cmf\Article\Model\Entity\Article';

    /**
     * @return AbstractFieldConfig
     */
    protected function getFieldsConfig()
    {
        return \Cmf\Component\Field\Factory::getConfig('Cmf\Article');
    }

    /**
     * @return \Cmf\Component\ActionLink\AbstractConfig
     */
    protected function getActionLinkConfig()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\Article', 'actionLink');

        $className = $config->configClass;

        return new $className();
    }

    /**
     * @param \Cmf\Article\Model\Entity\Article|BaseEntity $entity
     * @return $this
     */
    protected function fillEntityForCreate(BaseEntity $entity)
    {
        $entity->setUser(Application::getAuth()->getUser());

        return $this;
    }

    /**
     * @param \Cmf\Article\Model\Entity\Article|BaseEntity $entity
     */
    protected function fillEntityForEdit(BaseEntity $entity)
    {
    //		$entity->setUpdatingTime(time());
    }

    public function readAction()
    {
        $result = parent::readAction();
        if (!is_array($result)) {
            return $result;
        }

        $idContent = (int)Application::getRequest()->get('id');
        $commentForm = \Cmf\Comment\Form\Factory::create($this->request, ['idContent' => $idContent]);
        $commentTree = new \Cmf\Comment\Tree('Cmf\Article\Model\Entity\Comment', $idContent);

        return array_merge($result, [
            'commentForm' => $commentForm,
            'commentTree' => $commentTree,
        ]);
    }
}
