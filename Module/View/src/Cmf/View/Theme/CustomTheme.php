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

/**
 * Class for user's themes
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CustomTheme extends AbstractTheme
{
    /** @var AbstractEngine */
    protected $engine;

    /**
     * @param AbstractEngine $engine
     * @param string $themeName
     * @param string $parentThemeName
     */
    public function __construct(AbstractEngine $engine, $themeName, $parentThemeName)
    {
        $this->engine = $engine;
        $this->themeName = $themeName;
        $this->parentThemeName = $parentThemeName;
    }

    /**
     * @param null $object
     * @return AbstractEngine
     */
    protected function getEngine($object = null)
    {
        return $this->engine;
    }

    /**
     * @param string $className
     * @param string $postfix
     * @return bool
     */
    protected function searchForCurrentModule($className, $postfix = '')
    {
        $application = Application::getInstance();
        $mvcRequest = $application->getMvcRequest();
        $currentModule = $mvcRequest->getModuleName();
        $modulePath = str_replace('\\', '/', $currentModule);
        $controllerName = $mvcRequest->getControllerName();
        $actionName = $mvcRequest->getActionName();

        $result = false;
        while ($className) {
            $templatePath = str_replace('\\', DIRECTORY_SEPARATOR, $className);

            $pattern = '%s/Controller/%sController/action/%s/external/%s';
            $path = sprintf($pattern, $modulePath, $controllerName, $actionName, $templatePath);
            if (false !== $result = $this->assertTemplate($path . $postfix)) {
                break;
            }

            $path = sprintf('%s/Controller/%sController/external/%s', $modulePath, $controllerName, $templatePath);
            if (false !== $result = $this->assertTemplate($path . $postfix)) {
                break;
            }

            $path = sprintf('%s/external/%s', $modulePath, $templatePath);
            if (false !== $result = $this->assertTemplate($path . $postfix)) {
                break;
            }

            $className = get_parent_class($className);
        }

        return $result;
    }

    /**
     * @param string $path
     * @return bool|string
     */
    protected function assertTemplate($path)
    {
        $path = sprintf('%s/%s/%s/templates/%s', ROOT, ThemeFactory::THEME_FOLDER, $this->themeName, $path);

        return $this->getEngine()->templateExist($path) ? $path : false;
    }

    /**
     * @param string $className
     * @param string $postfix
     * @return bool|string
     */
    protected function searchIterative($className, $postfix = '')
    {
        $result = false;
        while ($className) {
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
            if (false !== $result = $this->assertTemplate($path . $postfix)) {
                break;
            }
            $className = get_parent_class($className);
        }

        return $result;
    }
}
