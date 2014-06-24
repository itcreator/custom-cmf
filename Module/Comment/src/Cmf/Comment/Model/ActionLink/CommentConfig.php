<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Model\ActionLink;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\Link\Constants as LinkConst;

/**
 * Action link configuration for categories
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CommentConfig extends AbstractConfig
{
    /** @var  int */
    protected $idContent;

    /** @var string */
    protected $moduleName;

    /**
     * @param int $idContent
     * @param string $moduleName
     */
    public function __construct($idContent, $moduleName)
    {
        $this->idContent = $idContent;
        $this->moduleName = $moduleName;
    }

    /**
     * @return $this
     */
    protected function init()
    {
        $lng = \Cmf\Language\Factory::get($this);

        $idContent = $this->idContent;

        $this->config = [
            'title' => $lng['actions'],
            'items' => [
                'create' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['reply'],
                    'url' => [
                        'module' => $this->moduleName,
                        'controller' => 'Comment',
                        'action' => 'create',
                        'idContent' => $idContent,
                    ],
                    'identifier' => 'idParent',
                    'class' => '',
                    'actions' => ['read'],
                ],
                'edit' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['edit'],
                    'url' => [
                        'module' => $this->moduleName,
                        'controller' => 'Comment',
                        'action' => 'edit',
                        'idContent' => $idContent,
                    ],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['read'],
                ],
                'delete' => [
                    'type' => LinkConst::TYPE_LINK,
                    'title' => $lng['delete'],
                    'url' => [
                        'module' => $this->moduleName,
                        'controller' => 'Comment',
                        'action' => 'delete',
                        'idContent' => $idContent,
                    ],
                    'identifier' => 'id',
                    'class' => '',
                    'actions' => ['read'],
                ],
            ],
        ];

        return $this;
    }
}
