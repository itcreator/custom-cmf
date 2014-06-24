<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Menu\Item;

use Cmf\Menu\Menu;
use Cmf\Menu\SubMenu;
use Cmf\System\Application;
use Zend\Config\Config as ZendConfig;

/**
 * Link menu item
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Link extends AbstractItem
{
    /** @var string */
    protected $title;

    /** @var  string */
    protected $hint;

    /** @var  string */
    protected $url;

    /** @var null|SubMenu */
    protected $menu = null;

    /** @var  null|string */
    protected $target;

    /**
     * @param ZendConfig $config
     * @param Menu|SubMenu $parent
     */
    public function __construct(ZendConfig $config, Menu $parent)
    {
        parent::__construct($config, $parent);

        $lng = $config->translate ? \Cmf\Language\Factory::get($config->translate) : null;

        $this->title = $lng ? $lng[$config->title] : $config->title;
        $this->hint = $lng ? $lng[$config->hint] : $config->hint;
        $this->target = $config->target;

        if ($config->menu) {
            $this->menu = new SubMenu($config->menu, $this);
        }
        $ub = Application::getUrlBuilder();
        $this->url = is_string($config->url) ? $config->url : $ub->build($config->url->toArray());
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed|string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * @return SubMenu|null
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return null|string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
