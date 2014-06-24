<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Theme;

use Cmf\System\Application;
use Cmf\View\Engine\AbstractEngine;
use Cmf\View\Engine\EngineFactory;
use Cmf\View\Theme\Exception\ThemeNotFoundException;

/**
 * Factory for themes
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ThemeFactory
{
    const MODULE_THEME = 'MODULE';

    const THEME_FOLDER = 'resources/theme/';

    /** @var array  */
    protected static $themes = [];

    /**
     * @param string $name
     * @return AbstractEngine
     */
    public static function getTheme($name)
    {
        if (isset(self::$themes[$name])) {
            return self::$themes[$name];
        }

        if (self::MODULE_THEME == $name) {
            $theme = self::getModuleTheme();
        } else {
            $theme = self::getCustomTheme($name);
        }

        self::$themes[$name] = $theme;

        return $theme;
    }

    /**
     * @param string $name
     * @return AbstractTheme
     * @throws Exception\ThemeNotFoundException
     */
    protected static function getCustomTheme($name)
    {
        $themeDir = ThemeFactory::THEME_FOLDER . $name . '/';

        if (file_exists($themeDir)) {
            $cm = Application::getConfigManager();
            $themeConfig = $cm->load('theme', null, $themeDir);
        } else {
            throw new ThemeNotFoundException(sprintf('Theme "%s" is not found', $name));
        }

        $engine = EngineFactory::get($themeConfig->engine);

        $themeClass = $themeConfig->render;

        $theme = new $themeClass($engine, $name, $themeConfig->parent);
        if (!($theme instanceof AbstractTheme)) {
            throw new ThemeNotFoundException('Theme render must be an instance of AbstractTheme');
        }

        return $theme;
    }

    /**
     * @return ModuleTheme
     */
    protected static function getModuleTheme()
    {
        $theme = new ModuleTheme(self::MODULE_THEME, null);

        return $theme;
    }
}
