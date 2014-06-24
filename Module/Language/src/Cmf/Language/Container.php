<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Language;

/**
 * Class for translations
 * Lazy loading for translation data
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Container implements \ArrayAccess
{
    /** @var array|null */
    protected $lng = null;
    /** @var string | Object */
    protected $className;
    /** @var string */
    protected $currentLanguage;
    /** @var string */
    protected $defaultLanguage;

    /**
     * @param string | Object $object
     * @param string $currentLanguage
     * @param string $defaultLanguage
     */
    public function __construct($object, $currentLanguage, $defaultLanguage)
    {
        $this->className = is_object($object) ? get_class($object) : $object;
        $this->currentLanguage = $currentLanguage;
        $this->defaultLanguage = $defaultLanguage;
    }

    public function __destruct()
    {
        $this->lng = null;
    }

    /**
     * @return Container
     */
    protected function load()
    {
        if (null === $this->lng) {
            $loader = new Loader($this->className, $this->currentLanguage, $this->defaultLanguage);
            $this->lng = $loader->load();
        }

        return $this;
    }

    /**
     * get language Value
     *
     * @param string $name
     * @return mixed
     */
    protected function get($name)
    {
        $this->load();

        return isset($this->lng[$name]) ? $this->lng[$name] : $name;
    }

    /**
     * ArrayAccess
     *
     * @param  $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return true;
    }

    /**
     * ArrayAccess
     *
     * @param  $offset
     * @return array|null|string
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * ArrayAccess
     *
     * @throws \Cmf\Language\Exception
     * @param  $offset
     * @param  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception('Set operation is denied.');
    }

    /**
     * ArrayAccess
     *
     * @throws \Cmf\Language\Exception
     * @param  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new Exception('Unset operation is denied.');
    }
}
