<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Structure\Collection\Ordered;

use Cmf\Structure\Collection\AssociateCollection;

/**
 * Collection with weights
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class OrderedCollection extends AssociateCollection
{
    /** @var array [itemName => weight] */
    protected $weights = [];

    /**
     * @return array
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * This method return current row
     * Iterator
     *
     * @return mixed
     */
    public function current()
    {
        $keys = array_keys($this->weights);

        return isset($keys[$this->counter]) ? $this->items[$keys[$this->counter]] : false;
    }

    /**
     * Iterator
     *
     * @return int
     */
    public function key()
    {
        $keys = array_keys($this->weights);

        return $keys[$this->counter];
    }

    /**
     * @param OrderedItemInterface $item
     * @param string $key
     * @return AssociateCollection
     * @throws ExceptionBadParams
     */
    public function setItem($item, $key = null)
    {
        if (!($item instanceof OrderedItemInterface)) {
            throw new ExceptionBadParams('Parameter $item must be instance of OrderedItemInterface');
        }
        parent::setItem($item, $key);

        $index = (null == $key || $key == (string)(int)$key) ? $this->maxIndex : $key;

        if (null == $weight = $item->getWeight()) {
            $weight = $this->weights ? max($this->weights) + 10 : 10;
        }

        $this->weights[$index] = $weight;
        asort($this->weights);

        return $this;
    }


    /**
     * Remove all items from collection
     *
     * @return AssociateCollection
     */
    public function clear()
    {
        $this->items = [];
        $this->weights = [];
        $this->rewind();

        return $this;
    }

    /**
     * ArrayAccess
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
            unset($this->weights[$offset]);
        }
    }

    /**
     * @param string $key
     * @return AssociateCollection
     */
    public function removeItem($key)
    {
        parent::removeItem($key);

        if (isset($this->weights[$key])) {
            unset($this->weights[$key]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function sort()
    {
        /** @var OrderedItemInterface $element */
        foreach ($this->items as $key => $element) {
            $this->weights[$key] = $element;
        }
        asort($this->weights);

        return $this;
    }
}
