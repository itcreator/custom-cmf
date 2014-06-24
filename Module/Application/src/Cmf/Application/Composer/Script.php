<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Application\Composer;

use Cmf\Standard\TSingleton;
use Cmf\System\Application;
use Composer\Script\CommandEvent;

/**
 * Script for composer events. It is part of the CMF installer
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Script
{
    use TSingleton;

    /**
     * @param CommandEvent $event
     */
    public static function installApp(CommandEvent $event)
    {
        $installer = self::getInstance();
        $installer
            ->createSymLinks($event)
            ->createIndexFile()
            ->createResourceFolders()
            ->copyDefaultConfig();
    }

    /**
     * @param CommandEvent $event
     */
    public static function initEnvironment(CommandEvent $event)
    {
        define('ROOT', getcwd() . '/');
        require ROOT . '/boot/bootstrap.php';

        $application = Application::getInstance();
        $application->init();
    }

    /**
     * @param string $dir
     * @return $this
     */
    public function createDir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function createIndexFile()
    {
        $modulePath = realpath(__DIR__ . '/../../../../');
        $root = getcwd() . '/';
        $publicDir = $root . 'public/';
        $this->createDir($publicDir);

        $indexLink = $publicDir . 'index.php';
        copy($modulePath . '/resources/public/index.php', $indexLink);
        chmod($indexLink, 0755);

        return $this;
    }

    /**
     * @return $this
     */
    protected function createSymLinks()
    {
        $root = getcwd() . '/';

        $modulePath = realpath(__DIR__ . '/../../../../');
        $resPath = $modulePath . '/resources/';
        $scriptsPath = $resPath . 'scripts/';

        $this
            ->createSymLink($scriptsPath, $root, 'bin')
            ->createSymLink($resPath, $root, 'boot');

        return $this;
    }

    /**
     * @param string $sourceFolder
     * @param string $linkFolder
     * @param string $object
     * @return $this
     */
    protected function createSymLink($sourceFolder, $linkFolder, $object)
    {
        $ok = true;
        if (!is_dir($sourceFolder)) {
            $ok = false;
        }

        if ($ok && file_exists($linkFolder . $object)) {
            $ok = false;
        }

        if ($ok) {
            symlink($sourceFolder . $object, $linkFolder . $object);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function createResourceFolders()
    {
        $root = getcwd() . '/';

        $this
            ->createDir($root . 'resources/')
            ->createDir($root . 'resources/config/')
            ->createDir($root . 'resources/config/ConfigInjection/')
            ->createDir($root . 'resources/language/')
            ->createDir($root . 'resources/theme/');

        return $this;
    }

    protected function copyDefaultConfig()
    {
        $modulePath = realpath(__DIR__ . '/../../../../');
        $defaultConfigDir = $modulePath . '/resources/defaultConfig/';
        $root = getcwd() . '/';
        $configDir = $root . 'resources/config/';
        copy($defaultConfigDir . 'ConfigInjection.cnf.xml', $configDir . 'ConfigInjection.cnf.xml');

        $configInjectionDir = $configDir . 'ConfigInjection/';
        $defaultConfigInjectionDir = $defaultConfigDir . 'ConfigInjection/';
        copy($defaultConfigInjectionDir . 'Cmf-Db.cnf.xml', $configInjectionDir . 'Cmf-Db.cnf.xml');
        copy($defaultConfigInjectionDir . 'Cmf-Mail.cnf.xml', $configInjectionDir . 'Cmf-Mail.cnf.xml');

        $publicResourcesName = 'Cmf-PublicResources.cnf.xml';
        copy($defaultConfigInjectionDir . $publicResourcesName, $configInjectionDir . $publicResourcesName);
    }
}
