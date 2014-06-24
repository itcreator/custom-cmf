<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Structure\Collection;

/**
 * Lazy associate collection
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class LazyAssociateCollection extends AssociateCollection
{
    /** @var \Closure */
    protected $initializer;

    /** @var bool  */
    protected $initialized = false;

    /**
     * @param \Closure $initializer function(LazyCollection $collection) { $collection->setItem(...); ...}
     */
    public function __construct(\Closure $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * @return $this
     */
    protected function init()
    {
        if (!$this->initialized) {
            $this->initialized = true;
            $fn = $this->initializer;
            $fn($this);
        }

        return $this;
    }

    /**
     * go to first row
     * Iterator
     */
    public function rewind()
    {
        $this->init();

        parent::rewind();
    }

    /**
     * This method return current row
     * Iterator
     *
     * @return mixed
     */
    public function current()
    {
        $this->init();

        return parent::current();
    }

    /**
     * Iterator
     *
     * @return int
     */
    public function key()
    {
        $this->init();

        return parent::key();
    }

    /**
     * Iterator
     *
     * @return Mixed
     */
    public function next()
    {
        $this->init();

        return parent::next();
    }

    /**
     * Iterator
     *
     * @return bool
     */
    public function valid()
    {
        $this->init();

        return parent::valid();
    }

    /**
     * @param mixed $item
     * @param string $key
     * @return AssociateCollection
     */
    public function setItem($item, $key = null)
    {
        $this->init();

        parent::setItem($item, $key);

        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->init();

        parent::setItems($items);

        return $this;
    }

    /**
     * Countable
     *
     * @return int
     */
    public function count()
    {
        $this->init();

        return parent::count();
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $this->init();

        return parent::offsetExists($offset);
    }

    /**
     * @param string $key
     * @return AssociateCollection
     */
    public function removeItem($key)
    {
        $this->init();

        return parent::removeItem($key);
    }
}
