<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category\Model\ActionLink;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\Link\Constants as LinkConst;

/**
 * Action link configuration for categories
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CategoryConfig extends AbstractConfig
{
    /**
     * @return $this
     */
    protected function init()
    {
        $lng = \Cmf\Language\Factory::get($this);

        $this->config = [
            'title' => $lng['actions'],
            'items' => [
                'list' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['toItemsList'],
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Category'],
                    'class' => '',
                    'actions' => ['edit', 'read', 'delete', 'create'],
                ],
                'read' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['view'],
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Category', 'action' => 'read'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['edit', 'default', 'delete'],
                ],
                'edit' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['edit'],
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Category', 'action' => 'edit'],
                    'identifier' => 'id',
                    'actions' => ['default', 'read', 'delete'],
                ],
                'delete' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['delete'],
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Category', 'action' => 'delete'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['default', 'read'],
                ],
            ],
        ];

        return $this;
    }
}
