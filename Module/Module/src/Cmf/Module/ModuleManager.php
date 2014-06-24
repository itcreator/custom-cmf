<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Module;

use Cmf\Module\Exception\ModuleNotFoundException;
use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ModuleManager
{
    /**
     * @param string $moduleName
     * @return string
     * @throws ModuleNotFoundException
     */
    public function getModulePath($moduleName)
    {
        $moduleName .= '\\';
        $loader = Application::getClassLoader();
        $paths = $loader->getPrefixes();
        if (!array_key_exists($moduleName, $paths)) {
            throw new ModuleNotFoundException(sprintf('Module "%s" is not found', $moduleName));
        }

        $path = $paths[$moduleName][0];
        return str_replace('/src', '', $path);
    }

    /**
     * @param string $className
     * @return string
     */
    public function getModuleNameByClass($className)
    {
        $arr = explode('\\', $className);
        if ('' == $arr[0]) {
            array_shift($arr);
        }

        $moduleNameArr = [array_shift($arr), array_shift($arr)];

        return implode('\\', $moduleNameArr);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getModuleNameByPath($path)
    {
        $path = str_replace(ROOT, '', $path);
        $arr = explode('/', $path);
        if ('' == $arr[0]) {
            array_shift($arr);
        }

        $moduleNameArr = [array_shift($arr), array_shift($arr)];

        return implode('\\', $moduleNameArr);
    }

    /**
     * @param string $className
     * @return string
     */
    public function getModulePathByClassName($className)
    {
        return $this->getModulePath($this->getModuleNameByClass($className));
    }

    /**
     * @param string $path
     * @return string
     */
    public function getModulePathByPath($path)
    {
        return $this->getModulePath($this->getModuleNameByPath($path));
    }
}
