<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Language;

use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    /** @var string */
    protected static $defaultLng;

    /** @var string */
    protected static $currentLng;

    /** @var array */
    protected static $cache = [];

    /**
     * Initialization of language manager
     *
     * @return void
     */
    private static function init()
    {
        if (!self::$defaultLng) {
            $config = Application::getConfigManager()->loadForModule('Cmf\Language');
            self::$defaultLng = strtolower($config->defaultLanguage);
            $currentLng = $config->currentLanguage;
            self::$currentLng = $currentLng ? strtolower($currentLng) : strtolower($config->defaultLanguage);
        }
    }

    /**
     * @static
     * @param $object
     * @return Container
     */
    public static function get($object)
    {
        self::init();
        $class = is_object($object) ? get_class($object) : $object;
        if (!isset(self::$cache[$class])) {
            self::$cache[$class] = new Container($object, self::$currentLng, self::$defaultLng);
        }

        return self::$cache[$class];
    }
}
