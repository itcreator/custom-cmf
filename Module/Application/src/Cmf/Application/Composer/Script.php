<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Application\Composer;

use Cmf\DataFixture\FixtureLoader;
use Cmf\Standard\TSingleton;
use Cmf\System\Application;
use Composer\Script\CommandEvent;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\Tools\SchemaTool;
use Zend\Config\Config as ZendConfig;
use Zend\Config\Reader\Xml as XmlConfig;
use Zend\Config\Writer\Xml as XmlWriter;

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
            ->copyHtaccess()
            ->createResourceFolders()
            ->copyDefaultConfig($event)
            ->updateSchema($event)
            ->loadFixtures($event)
        ;
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
    protected function copyHtaccess()
    {
        $modulePath = realpath(__DIR__ . '/../../../../');
        $root = getcwd() . '/';
        $publicDir = $root . 'public/';
        $this->createDir($publicDir);

        $destFile = $publicDir . '.htaccess';
        copy($modulePath . '/resources/public/.htaccess', $destFile);
        chmod($destFile, 0755);

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
            ->createDir($root . 'resources/theme/')
            ->createDir($root . 'tmp/');

        return $this;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return bool true if file is new
     */
    protected function copyConfigFile($source, $destination)
    {
        $fileExist = file_exists($destination);
        if (!$fileExist) {
            copy($source, $destination);
        }

        return !$fileExist;
    }

    /**
     * @param CommandEvent $event
     * @param string $dbConfigPath
     * @return $this
     */
    protected function configureDb(CommandEvent $event, $dbConfigPath)
    {
        $reader = new XmlConfig();
        $config = new ZendConfig($reader->fromFile($dbConfigPath), true);
        $io = $event->getIO();

        $passwordValidator = function ($value) {
            if (!preg_match('/^([0-9a-zA-Z_~\-`@"]+)*$/u', $value)) {
                throw new \Exception('Only this symbols 0-9 a-z A-Z _ ~ \ - ` @ "');
            }

            return $value;
        };

        $nameValidator = function ($value) {
            if (!preg_match('/^([0-9a-zA-Z]+)$/u', $value)) {
                throw new \Exception('Only this symbols 0-9 a-z A-Z "');
            }

            return $value;
        };

        $hostValidator = function ($value) {
            if (!preg_match('/^([0-9a-z\.]+)*$/u', $value)) {
                throw new \Exception('Only this symbols 0-9 a-z . "');
            }
            return $value;
        };

        $io->write('Data base configuration');
        $path = $io->askAndValidate('Path to data base [localhost]: ', $hostValidator, false, 'localhost');
        $dbName = $io->askAndValidate('Data base name: ', $nameValidator);
        $userName = $io->askAndValidate('User name for DB: ', $nameValidator);
        $password = $io->askAndValidate('User password for DB: ', $passwordValidator);


        $config->connection->path = $path;
        $config->connection->dbname = null;
        $config->connection->user = $userName;
        $config->connection->password = $password;

        $writer = new XmlWriter();
        $writer->toFile($dbConfigPath, $config);

        $this->createDataBase($event, $dbName);

        $config->connection->dbname = $dbName;

        $writer = new XmlWriter();
        $writer->toFile($dbConfigPath, $config);

        return $this;
    }

    /**
     * @param CommandEvent $event
     * @param string $dbName
     * @return $this
     * @throws \Doctrine\DBAL\Exception\DriverException
     */
    protected function createDataBase(CommandEvent $event, $dbName)
    {
        $query = sprintf('CREATE DATABASE %s DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;', $dbName);
        /** @var \Doctrine\DBAL\Driver\PDOMySql\Driver $driver */

        $created = true;
        $connection = Application::getEntityManager()->getConnection();
        try {
            $connection->executeQuery($query);
        } catch (DriverException $e) {
            //if database already exist
            if (1007 !== $e->getErrorCode()) {
                throw $e;
            }

            $created = false;
        }

        Application::getInstance()->resetEntityManager();

        if ($created) {
            $msg = sprintf('Database "%s" is created', $dbName);
        } else {
            $msg = sprintf('Database "%s" already exist', $dbName);
        }

        $event->getIO()->write($msg);

        return $this;
    }

    /**
     * @param CommandEvent $event
     * @return $this
     */
    protected function copyDefaultConfig(CommandEvent $event)
    {
        $modulePath = realpath(__DIR__ . '/../../../../');
        $defaultConfigDir = $modulePath . '/resources/defaultConfig/';
        $root = getcwd() . '/';
        $configDir = $root . 'resources/config/';
        $this->copyConfigFile($defaultConfigDir . 'ConfigInjection.cnf.xml', $configDir . 'ConfigInjection.cnf.xml');

        $configInjectionDir = $configDir . 'ConfigInjection/';
        $defaultCiDir = $defaultConfigDir . 'ConfigInjection/';
        $publicResourcesName = 'Cmf-PublicResources.cnf.xml';

        $this->copyConfigFile($defaultCiDir . 'Cmf-Mail.cnf.xml', $configInjectionDir . 'Cmf-Mail.cnf.xml');
        $this->copyConfigFile($defaultCiDir . $publicResourcesName, $configInjectionDir . $publicResourcesName);

        $dbConfigPath = $configInjectionDir . 'Cmf-Db.cnf.xml';
        if ($this->copyConfigFile($defaultCiDir . 'Cmf-Db.cnf.xml', $dbConfigPath)) {
            $this->configureDb($event, $dbConfigPath);
        }

        return $this;
    }

    /**
     * @param CommandEvent $event
     * @return $this
     */
    protected function updateSchema(CommandEvent $event)
    {
        $io = $event->getIO();
        $em = Application::getEntityManager();
        $metaData = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($em);

        $io->write('Updating database schema...');
        $schemaTool->updateSchema($metaData, true);
        $io->write('Database schema updated successfully!');

        return $this;
    }


    /**
     * @param CommandEvent $event
     * @return $this
     */
    public function loadFixtures(CommandEvent $event)
    {
        $f = new FixtureLoader();
        $f->load();

        $event->getIO()->write('Fixtures loaded successfully');

        return $this;
    }
}
