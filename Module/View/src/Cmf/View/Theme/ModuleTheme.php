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
use Cmf\View\Theme\Exception\TemplateNotFoundException;

/**
 * Class for templates from module folder
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ModuleTheme extends AbstractTheme
{
    /** @var AbstractEngine[] */
    protected $engines = [];

    /**
     * @param string $themeName
     * @param string $parentThemeName
     */
    public function __construct($themeName, $parentThemeName)
    {
        $this->themeName = $themeName;
        $this->parentThemeName = $parentThemeName;
    }

    /**
     * @param string $className
     * @param string $postfix
     * @return bool|string
     */
    protected function searchForCurrentModule($className, $postfix = '')
    {
        $application = Application::getInstance();
        $mvcRequest = $application->getMvcRequest();
        $currentModule = $mvcRequest->getModuleName();
        $currentModulePath = str_replace('\\', '/', $currentModule);
        $controllerName = $mvcRequest->getControllerName();
        $actionName = $mvcRequest->getActionName();

        $result = false;
        while ($className) {
            $templatePath = str_replace('\\', DIRECTORY_SEPARATOR, $className);

            //search in action
            $pattern = '%s/Controller/%sController/action/%s/external/%s';
            $path = sprintf($pattern, $currentModulePath, $controllerName, $actionName, $templatePath);
            if (false !== $result = $this->assertTemplateForCurrentModule($path . $postfix, $currentModule)) {
                break;
            }

            //search in controller
            $pattern = '%s/Controller/%sController/external/%s';
            $path = sprintf($pattern, $currentModulePath, $controllerName, $templatePath);
            if (false !== $result = $this->assertTemplateForCurrentModule($path . $postfix, $currentModule)) {
                break;
            }

            //search in module
            $path = sprintf('%s/external/%s', $currentModulePath, $templatePath);
            if (false !== $result = $this->assertTemplateForCurrentModule($path . $postfix, $currentModule)) {
                break;
            }

            $className = get_parent_class($className);
        }

        return $result;
    }

    /**
     * @param string $fullPath
     * @param string $currentModuleName
     * @return bool|string
     */
    public function assertTemplateForCurrentModule($fullPath, $currentModuleName)
    {
        $engine = $this->getEngineForCurrentModule($currentModuleName);

        $moduleManager = Application::getModuleManager();
        $modulePath = $moduleManager->getModulePath($currentModuleName);
        $fullPath = sprintf('%s/%stemplates/%s', $modulePath, ThemeFactory::THEME_FOLDER, $fullPath);

        return $engine && $engine->templateExist($fullPath) ? ['path' => $fullPath, 'engine' => $engine] : false;
    }

    /**
     * @param string $currentModuleName
     * @return AbstractEngine|null
     */
    protected function getEngineForCurrentModule($currentModuleName)
    {
        $moduleManager = Application::getModuleManager();
        $modulePath = $moduleManager->getModulePath($currentModuleName);

        $cm = Application::getConfigManager();
        $themeConfig = $cm->load('theme', null, $modulePath . '/' . ThemeFactory::THEME_FOLDER);

        return $themeConfig->engine ? EngineFactory::get($themeConfig->engine) : null;
    }

    /**
     * @param null $path
     * @return AbstractEngine
     */
    protected function getEngine($path = null)
    {
        $moduleManager = Application::getModuleManager();
        $modulePath = $moduleManager->getModulePathByPath($path);

        $cm = Application::getConfigManager();
        $themeConfig = $cm->load('theme', null, $modulePath . '/' . ThemeFactory::THEME_FOLDER);

        return $themeConfig->engine ? EngineFactory::get($themeConfig->engine) : null;
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

    /**
     * @param string $path
     * @return bool|string
     */
    protected function assertTemplate($path)
    {
        $moduleManager = Application::getModuleManager();
        $modulePath = $moduleManager->getModulePathByPath($path);
        $fullPath = sprintf('%s/%stemplates/%s', $modulePath, ThemeFactory::THEME_FOLDER, $path);
        $engine = $this->getEngine($path);

        return $engine && $engine->templateExist($fullPath) ? ['path' => $fullPath, 'engine' => $engine] : false;
    }

    /**
     * @param string|object $object string or object
     * @param array $data
     * @param string $postfix
     * @return string
     * @throws TemplateNotFoundException
     */
    public function render($object, array $data = [], $postfix = '')
    {
        if ($result = $this->getTemplatePath($object, $postfix)) {
            //search template in module folders
            $data = array_merge($data, ['this' => Application::getViewProcessor(), 'object' => $object]);

            /** @var AbstractEngine $engine */
            $engine = $result['engine'];
            $result = $engine->render($result['path'], $data);
        } else {
            $object = is_object($object) ?  get_class($object) : $object;
            throw new TemplateNotFoundException(sprintf('Template %s not found', $object));
        }

        return $result;
    }
}
