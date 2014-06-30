<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\ActionLink;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\Link\Constants as LinkConst;
use Cmf\System\Application;

/**
 * Action link configuration for articles
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ArticleConfig extends AbstractConfig
{
    /** @var string  */
    protected $controllerName = 'Article';

    /**
     * @return $this
     */
    protected function init()
    {
        $lng = \Cmf\Language\Factory::get($this);

        $mm = Application::getModuleManager();
        $moduleName = $mm->getModuleNameByClass(get_class($this));

        $this->config = [
            'title' => $lng['actions'],
            'items' => [
                'default' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['toItemsList'],
                    'url' => ['module' => $moduleName, 'controller' => $this->controllerName],
                    'class' => '',
                    'actions' => ['edit', 'read', 'delete', 'create'],
                ],
                'read' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['view'],
                    'url' => ['module' => $moduleName, 'controller' => $this->controllerName, 'action' => 'read'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['edit', 'default', 'delete'],
                ],
                'edit' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['edit'],
                    'url' => ['module' => $moduleName, 'controller' => $this->controllerName, 'action' => 'edit'],
                    'identifier' => 'id',
                    'actions' => ['default', 'read', 'delete'],
                ],
                'delete' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['delete'],
                    'url' => ['module' => $moduleName, 'controller' => $this->controllerName, 'action' => 'delete'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['default', 'read'],
                ],
            ],
        ];

        return $this;
    }
}
