<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Config;

use Cmf\System\Application;

use Zend\Config\Reader\Xml as XmlConfig;
use Zend\Config\Config as ZendConfig;

/**
 * Config manager
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ConfigManager
{
    /** @var array array cache for config files */
    protected $config = [];

    /**
     * @param string $name
     * @param string|null $root
     * @param bool $refresh
     * @return mixed|null|ZendConfig
     */
    protected function loadConfig($name, $root, $refresh)
    {
        if (isset($this->config[$name]) && !$refresh) {
            $conf = $this->config[$name];
        } else {
            $path = $name . '.cnf.xml';
            $reader = new XmlConfig();
            $conf = file_exists($path) ? new ZendConfig($reader->fromFile($path)) : new ZendConfig([]);
            $this->config[$name] = $conf;
        }

        if ($root) {
            $result = $conf ? $conf->$root : new ZendConfig([]);
        } else {
            $result = $conf;
        }

        if (!($result instanceof ZendConfig)) {
            $result = new ZendConfig([]);
        }

        return $result;
    }

    /**
     * @param $name
     * @param string|null $root
     * @param string|null $path
     * @param bool $refresh
     * @return mixed|null|ZendConfig
     */
    public function load($name, $root = null, $path = null, $refresh = false)
    {
        if ($path) {
            $name = $path . $name;
        } else {
            $name = 'resources/config/' . $name;
        }

        return $this->loadConfig($name, $root, $refresh);
    }

    /**
     * @param string $moduleName
     * @param string|null $root
     * @param bool $refresh
     * @return mixed|null|ZendConfig
     */
    public function loadForModule($moduleName, $root = null, $refresh = false)
    {
        $mm = Application::getModuleManager();
        $moduleName = ucfirst($moduleName);
        $modulePath = sprintf('%s/resources/config/module', $mm->getModulePath($moduleName));

        try {
            $config = $this->loadConfig($modulePath, $root, $refresh);
            $this->injectConfig($config, $moduleName, $root, $refresh);

            return $config;
        } catch (\Exception $e) {
            return new ZendConfig([]);
        }
    }

    /**
     * @param ZendConfig|null $config
     * @param string $moduleName
     * @param string|null $root
     * @param bool $refresh
     * @return $this
     */
    protected function injectConfig($config, $moduleName, $root, $refresh)
    {
        $moduleConfigFile = str_replace('\\', '-', $moduleName);
        $injectionConfig = $this->load('ConfigInjection', $moduleConfigFile);

        if ($injectionConfig) {
            foreach ($injectionConfig as $injectionModule => $c) {
                $injectionModule = str_replace('-', '\\', $injectionModule);
                $mm = Application::getModuleManager();
                $modulePathTpl = '%s/resources/config/ConfigInjection/%s';
                $injectedModulePath = sprintf($modulePathTpl, $mm->getModulePath($injectionModule), $moduleConfigFile);

                $injection = $this->loadConfig($injectedModulePath, $root, $refresh);
                $config->merge($injection);
            }
        }

        //Inject global config
        $injectedModulePath = sprintf('%sresources/config/ConfigInjection/%s', ROOT, $moduleConfigFile);
        $injection = $this->loadConfig($injectedModulePath, $root, $refresh);
        $config->merge($injection);

        return $this;
    }
}
