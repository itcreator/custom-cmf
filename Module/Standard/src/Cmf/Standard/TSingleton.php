<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Standard;

/**
 * Trait for singletons
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
trait TSingleton
{
    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @return $this instance of a object
     */
    public static function getInstance()
    {
        static $instance;
        if (!$instance) {
            $instance = new static();
        }

        return $instance;
    }
}
