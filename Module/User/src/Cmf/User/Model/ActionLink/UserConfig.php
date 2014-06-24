<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Model\ActionLink;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\Link\Constants as LinkConst;

/**
 * Action link configuration for user module
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class UserConfig extends AbstractConfig
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
                    'url' => ['module' => 'Cmf\User', 'controller' => 'User'],
                    'class' => '', '
                    actions' => ['edit', 'read', 'delete', 'create'],
                ],
                'read' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['view'],
                    'url' => ['module' => 'Cmf\User', 'controller' => 'User', 'action' => 'read'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['edit', 'default', 'delete'],
                ],
                'edit' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['edit'],
                    'url' => ['module' => 'Cmf\User', 'controller' => 'User', 'action' => 'edit'],
                    'identifier' => 'id',
                    'actions' => ['default', 'read', 'delete'],
                ],
                'delete' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['delete'],
                    'url' => ['module' => 'Cmf\User', 'controller' => 'User', 'action' => 'delete'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['default', 'read'],
                ],
            ],
        ];

        return $this;
    }
}
