<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Helper;

use Cmf\System\Application;

/**
 * View Helper Factory
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @method static JS getJS()
 * @method static MessageBox getMessageBox()
 * @method static Meta getMeta()
 * @method static Style getStyle()
 * @method static Title getTitle()
 */
class HelperFactory
{
    /** @var array helpers array */
    protected static $helpers = [];

    /**
     * @throws \Cmf\View\Exception
     * @param string $helperName
     * @return \Cmf\View\Helper\AbstractHelper
     */
    public static function get($helperName)
    {
        if (!isset(self::$helpers[$helperName])) {
            self::initHelper($helperName);
        }

        return self::$helpers[$helperName];
    }

    /**
     * Facade for helpers
     *
     * @param string $name
     * @param array $arguments
     * @return array|null|object
     */
    public static function __callStatic($name, $arguments)
    {
        $service = null;
        if ('get' == substr($name, 0, 3)) {
            $key = substr($name, 3);
            $service = self::get($key);
        }

        return $service;
    }

    /**
     * @throws \Cmf\View\Exception
     * @param string $helperName
     * @return void
     */
    public static function initHelper($helperName)
    {
        $loader = Application::getClassLoader();
        $helperClass = 'Cmf\\View\Helper\\' . $helperName;

        if ($loader->findFile($helperClass)) {
            self::$helpers[$helperName] = new $helperClass();
        } else {
            throw new \Cmf\View\Exception(sprintf('View helper "%s" does not exist', $helperName));
        }
    }
}
