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

/**
 * Action link configuration for articles
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ArticleConfig extends AbstractConfig
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
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Article'],
                    'class' => '',
                    'actions' => ['edit', 'read', 'delete', 'create'],
                ],
                'read' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['view'],
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Article', 'action' => 'read'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['edit', 'default', 'delete'],
                ],
                'edit' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['edit'],
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Article', 'action' => 'edit'],
                    'identifier' => 'id',
                    'actions' => ['default', 'read', 'delete'],
                ],
                'delete' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['delete'],
                    'url' => ['module' => 'Cmf\Article', 'controller' => 'Article', 'action' => 'delete'],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['default', 'read'],
                ],
            ],
        ];

        return $this;
    }
}
