<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */


namespace Cmf\Menu;

use Cmf\Menu\Item\AbstractItem;
use Cmf\Structure\Collection\Ordered\OrderedCollection;

use Zend\Config\Config as ZendConfig;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Menu
{
    //TODO: use ordered collection
    /** @var OrderedCollection|AbstractItem[] */
    protected $items;

    /** @var  AbstractItem|null */
    protected $parent;

    /**
     * @param ZendConfig $config
     * @param null $parent
     */
    public function __construct(ZendConfig $config = null, $parent = null)
    {
        $this->items = new OrderedCollection();

        if ($config) {
            foreach ($config as $itemConfig) {
                if ($item = \Cmf\Menu\Item\Factory::create($itemConfig, $this)) {
                    $this->items->setItem($item);
                }
            }
        }

        $this->parent = $parent;
    }

    /**
     * @return array|AbstractItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return AbstractItem|null
     */
    public function getParent()
    {
        return $this->parent;
    }
}
