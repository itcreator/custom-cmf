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
 * Class for loading language sources
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Loader
{
    /** @var string */
    protected static $languagesDir = 'resources/language/';

    /** @var string */
    protected static $extension = 'lng.php';

    /** @var string  */
    protected $className;

    /** @var string */
    protected $currentLanguage;

    /** @var string */
    protected $defaultLanguage;

    /**
     * @param string $className
     * @param string $currentLanguage
     * @param string $defaultLanguage
     */
    public function __construct($className, $currentLanguage, $defaultLanguage)
    {
        $this->className = $className;
        $this->currentLanguage = $currentLanguage;
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @param string $className
     * @return string
     */
    protected function getPathByClassName($className)
    {
        $mm = Application::getModuleManager();
        $moduleName = $mm->getModuleNameByClass($className);
        $modulePath = $mm->getModulePath($moduleName);
        $internalPath = str_replace('\\', '/', str_replace($moduleName . '\\', '', $className));

        $pathPattern = '%s/%s%s/%s.%s';
        $path = sprintf($pathPattern, $modulePath, self::$languagesDir, '%s', $internalPath, self::$extension);

        return $path;
    }

    /**
     * @param string $fileName
     * @return array
     * @throws Exception
     */
    protected function loadLngFile($fileName)
    {
        $result = null;
        if (file_exists($fileName)) {
            $result = include($fileName);
            if ($result && !is_array($result)) {
                throw new Exception(sprintf('Language file "%s" must return array', $fileName));
            }
        }
        if (!$result) {
            $result = [];
        }

        return $result;
    }

    /**
     * @param string $path
     * @return array|string[]
     */
    protected function loadLngByPath($path)
    {
        $currentLngPath = sprintf($path, $this->currentLanguage);
        $currentLng = self::loadLngFile($currentLngPath);
        if ($this->currentLanguage == $this->defaultLanguage) {
            $lng =  $currentLng;
        } else {
            $defaultLngPath = sprintf($path, $this->defaultLanguage);
            $defaultLng = self::loadLngFile($defaultLngPath);
            $lng = array_merge($defaultLng, $currentLng);
        }

        return $lng;
    }

    /**
     * @return array|string[]
     */
    public function searchIterative()
    {
        $lng = [];
        $className = $this->className;
        while ($className) {
            $path = self::getPathByClassName($className);
            $lng = array_merge($this->loadLngByPath($path), $lng);
            $className = get_parent_class($className);
        }

        return $lng;
    }

    /**
     * @return array|string[]
     */
    public function searchInCurrentModule()
    {
        $application = Application::getInstance();
        $currentModule = $application->getMvcRequest()->getModuleName();
        $classPath = str_replace('\\', '/', $this->className);

        $mm = Application::getModuleManager();
        $modulePath = $mm->getModulePath($currentModule);
        $pathPattern = '%s/%s%s/external/%s.%s';
        $path = sprintf($pathPattern, $modulePath, self::$languagesDir, '%s', $classPath, self::$extension);

        return $this->loadLngByPath($path);
    }

    /**
     * @return array|string[]
     */
    public function searchForApplication()
    {
        $classPath = str_replace('\\', '/', $this->className);
        $path = sprintf('%s%s/%s.%s', self::$languagesDir, '%s', $classPath, self::$extension);

        return $this->loadLngByPath($path);
    }

    /**
     * @return array|string[]
     */
    public function load()
    {
        $iterativeLng = $this->searchIterative();
        $currentModuleLng = $this->searchInCurrentModule();
        $applicationLng = $this->searchForApplication();
        $lng = array_merge($iterativeLng, $currentModuleLng, $applicationLng);

        return $lng;
    }
}
