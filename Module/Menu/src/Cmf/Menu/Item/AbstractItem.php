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
use Cmf\Structure\Collection\Ordered\OrderedItemInterface;
use Cmf\Structure\Collection\Ordered\OrderedItemTrait;
use Zend\Config\Config as ZendConfig;

/**
 * Abstract menu item
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractItem implements OrderedItemInterface
{
    use OrderedItemTrait;

    /** @var Menu|SubMenu */
    protected $parent;

    /**
     * @param ZendConfig $config
     * @param Menu|SubMenu $parent
     */
    public function __construct(ZendConfig $config, Menu $parent)
    {
        $this->weight = $config->weight;
        $this->parent = $parent;
    }

    /**
     * @return Menu|SubMenu
     */
    public function getParent()
    {
        return $this->parent;
    }
}
