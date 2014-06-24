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
class AssociateCollection extends SimpleCollection implements \ArrayAccess
{
    /** @var int|null */
    protected $maxIndex = null;

    /**
     * go to first row
     * Iterator
     *
     * @return void
     */
    public function rewind()
    {
        $this->counter = 0;
        $this->maxIndex = 0;
    }

    /**
     * This method return current row
     * Iterator
     *
     * @return mixed
     */
    public function current()
    {
        $keys = array_keys($this->items);

        return isset($keys[$this->counter]) ? $this->items[$keys[$this->counter]] : false;
    }

    /**
     * Iterator
     *
     * @return int
     */
    public function key()
    {
        $keys = array_keys($this->items);

        return $keys[$this->counter];
    }

    /**
     * @return AssociateCollection
     */
    protected function incrementMaxIndex()
    {
        if (null === $this->maxIndex) {
            $this->maxIndex = 0;
        } else {
            ++ $this->maxIndex;
        }

        return $this;
    }

    /**
     * @param mixed $item
     * @param string $key
     * @return AssociateCollection
     */
    public function setItem($item, $key = null)
    {
        if (null == $key) {
            $this->incrementMaxIndex();
            $this->items[$this->maxIndex] = $item;
        } elseif ($key == (string)(int)$key) {
            if ($key >= $this->maxIndex) { // >= for null
                $this->maxIndex = (int)$key;
                $this->items[$this->maxIndex] = $item;
            }
        } else {
            $this->items[$key] = $item;
        }

        return $this;
    }

    /**
     * @param array $items
     * @return AssociateCollection
     */
    public function setItems($items)
    {
        foreach ($items as $key => $item) {
            $this->setItem($item, $key);
        }

        return $this;
    }

    /**
     * @param string $key
     * @return mixed |null
     */
    public function getItem($key)
    {
        return $this->offsetExists($key) ? $this->items[$key] : null;
    }

    /**
     * Remove all items from collection
     *
     * @return AssociateCollection
     */
    public function clear()
    {
        $this->items = [];
        $this->rewind();

        return $this;
    }

    /**
     * ArrayAccess
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * ArrayAccess
     *
     * @param  $offset
     * @return null|string
     */
    public function offsetGet($offset)
    {
        return $this->getItem($offset);
    }

    /**
     * ArrayAccess
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->setItem($value, $offset);
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
        }
    }

    /**
     * @param string $key
     * @param mixed $item
     * @return void
     */
    public function __set($key, $item)
    {
        $this->setItem($item, $key);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->getItem($key);
    }

    /**
     * @param string $key
     * @return AssociateCollection
     */
    public function removeItem($key)
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }

        return $this;
    }
}
