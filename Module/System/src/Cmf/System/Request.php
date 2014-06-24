<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\System;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Request
{
    const TYPE_POST = 'post';
    const TYPE_GET = 'get';
    const TYPE_ANY = 'any';

    /** @var array */
    protected $vars = [];

    /** @var array */
    protected $sorts = [];

    /**
     * This method initialize a Request object
     *
     */
    public function __construct()
    {
        $this->clear();
        $this->initAllVars();
    }

    /**
     * This method read all data from HTTP request to protected arrays
     *
     * @return void
     */
    protected function initAllVars()
    {
        Application::getUrlBuilder()->getAdapter()->initRequestVariables($this);
    }

    /**
     * @param string $type
     * @param array $data
     * @return void
     */
    public function initVars($type, $data)
    {
        $this->vars[$type] = [];
        foreach ($data as $key => $val) {
            $this->vars[$type][$key] = $val;
        }
    }

    /**
     * This method return request value
     *
     * @param string $name
     * @param string $type
     * @param null $default
     * @return null|mixed
     */
    public function get($name, $type = self::TYPE_GET, $default = null)
    {
        return (isset($this->vars[$type]) && isset($this->vars[$type][$name])) ? $this->vars[$type][$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    public function set($name, $value, $type = self::TYPE_GET)
    {
        if (self::TYPE_GET == $type || self::TYPE_POST == $type) {
            $this->vars[$type][$name] = $value;
            $this->vars[self::TYPE_ANY][$name] = $value;

            $result = true;
        } elseif (self::TYPE_ANY == $type) {
            $this->vars[self::TYPE_ANY][$name] = $value;
            $this->vars[self::TYPE_GET][$name] = $value;
            $this->vars[self::TYPE_POST][$name] = $value;

            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * @param string $type
     * @param array $data
     * @return void
     */
    public function setVars($type, $data)
    {
        if (!isset($this->vars[$type])) {
            $this->vars[$type] = [];
        }
        foreach ($data as $key => $val) {
            $this->vars[$type][$key] = $val;
            if (self::TYPE_ANY != $type) {
                $this->vars[self::TYPE_ANY][$key] = $val;
            }
        }
    }

    /**
     * @param string $type
     * @return array|null
     */
    public function getVars($type = self::TYPE_GET)
    {
        if (self::TYPE_GET == $type || self::TYPE_POST == $type || self::TYPE_ANY == $type) {
            return isset($this->vars[$type]) ? $this->vars[$type] : null;
        }

        return [];
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->vars = [
            self::TYPE_POST => [],
            self::TYPE_GET => [],
            self::TYPE_ANY => [],
        ];
    }

    /**
     * @static
     * @param string $requestVariable
     * @return \Cmf\System\Sort
     */
    public function getSort($requestVariable = 'sort')
    {
        if (!isset($this->sorts[$requestVariable])) {
            $sort = new Sort($requestVariable);
            $sort->setFromRequest();
            $this->sorts[$requestVariable] = $sort;
        }

        return $this->sorts[$requestVariable];
    }
}
