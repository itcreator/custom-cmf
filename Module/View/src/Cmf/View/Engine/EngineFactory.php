<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Engine;

use Composer\Autoload\ClassLoader;
use Cmf\System\Application;
use Cmf\View\Exception;

/**
 * View template engine factory
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class EngineFactory
{
    /** @var array Array of abstract engines */
    protected static $engines = [];

    /**
     * @param string $engineName
     * @throws Exception
     * @return void
     */
    protected static function initEngine($engineName)
    {
        if (isset(self::$engines[$engineName])) {
            return;
        }

        /** @var ClassLoader $loader */
        $loader = Application::get('ClassLoader');
        $engineClass = $engineName;
        if ($loader->findFile($engineClass)) {
            if (empty(self::$engines[$engineName])) {
                self::$engines[$engineName] = [];
            }
            $engine = new $engineClass();

            if (!($engine instanceof AbstractEngine)) {
                $msg = 'Template engine "%s" must be instance of Cmf\View\Engine\AbstractEngine';
                throw new Exception(sprintf($msg, $engineName));
            }
            self::$engines[$engineName] = $engine;
        } else {
            throw new Exception(sprintf('Template engine "%s" does not exist', $engineName));
        }

    }

    /**
     * It's factory method
     *
     * @param string $engineName
     * @return AbstractEngine
     */
    public static function get($engineName)
    {
        self::initEngine($engineName);

        return self::$engines[$engineName];
    }
}
