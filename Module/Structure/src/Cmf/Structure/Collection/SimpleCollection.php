<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Structure\Collection;

/**
 * Class for filters collection
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class SimpleCollection implements \Iterator, \Countable
{
    /**
     * @var array array of collection items
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * go to first row
     * Iterator
     *
     * @return void
     */
    public function rewind()
    {
        $this->counter = 0;
    }

    /**
     * This method return current row
     * Iterator
     *
     * @return mixed
     */
    public function current()
    {
        return isset($this->items[$this->counter]) ? $this->items[$this->counter] : false;
    }

    /**
     * Iterator
     *
     * @return int
     */
    public function key()
    {
        return $this->counter;
    }

    /**
     * Iterator
     *
     * @return Mixed
     */
    public function next()
    {
        ++ $this->counter;

        return $this->current();
    }

    /**
     * Iterator
     *
     * @return bool
     */
    public function valid()
    {
        return count($this->items) > $this->counter;
    }

    /**
     * @param Mixed $item
     * @return $this
     */
    public function setItem($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }

        return $this;
    }

    /**
     * Remove all items from collection
     *
     * @return $this
     */
    public function clear()
    {
        $this->items = [];
        $this->rewind();

        return $this;
    }

    /**
     * Countable
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }
}
