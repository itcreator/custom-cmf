<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Db;

use Cmf\System\Application;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\Driver\DriverChain;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Doctrine
{
    const PROXY_DIR = '/tmp/Proxy';
    /**
     * @var EntityManager
     */
    protected static $em = null;

    /**
     * @param \Doctrine\ORM\Configuration $config
     * @return ArrayCache
     */
    protected function initCache(Configuration $config)
    {
        $cache = new ArrayCache();
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        return $cache;
    }

    /**
     * @param \Doctrine\ORM\Configuration $config
     * @return Doctrine
     */
    public function initProxies(Configuration $config)
    {
        $config->setProxyDir(ROOT . self::PROXY_DIR);
        $config->setProxyNamespace('Proxy');

        // Proxy configuration
        if ("development" == APPLICATION_MODE) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        return $this;
    }

    /**
     * @param \Doctrine\ORM\Configuration $config
     * @return Doctrine
     */
    protected function initLogger($config)
    {
        //logger
        if ('development' == APPLICATION_MODE) {
//            $config->setSQLLogger($logger);
        }

        return $this;
    }

    /**
     * @param bool $reload
     * @return EntityManager|null
     */
    public function getEm($reload = false)
    {
        if (!self::$em || $reload) {
            $this->initEm($reload);
        }

        return self::$em;
    }

    /**
     * @param bool $reload
     * @return $this
     */
    public function initEm($reload = false)
    {
        $config = new Configuration();
        $cache = $this->initCache($config);

        AnnotationRegistry::registerFile("Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");

        // standard annotation reader
        $annotationReader = new \Doctrine\Common\Annotations\AnnotationReader;
        $cachedAnnotationReader = new \Doctrine\Common\Annotations\CachedReader($annotationReader, $cache);

        // create a driver chain for metadata reading
        $driverChain = new \Doctrine\ORM\Mapping\Driver\DriverChain();

        // now we want to register our application entities,
        // for that we need another metadata driver used for Entity namespace
        // paths to look in
        $paths = Application::getConfigManager()->loadForModule('Cmf\Db', 'entityPath', $reload)->toArray();
        $realPaths = [];
        $mm = Application::getModuleManager();

        foreach ($paths as $key => $path) {
            $modulePath = $mm->getModulePathByPath($path);
            $realPaths[str_replace('-', '\\', $key)] = $modulePath . '/src/' . $path;
        }

        $annotationDriver = new AnnotationDriver($cachedAnnotationReader, $realPaths);
        foreach (array_keys($realPaths) as $nameSpace) {
            $driverChain->addDriver($annotationDriver, $nameSpace);
        }

        // general ORM configuration
        $this->initProxies($config)->initLogger($config);

        // register metadata driver
        $config->setMetadataDriverImpl($driverChain);

        $evm = $this->initExtensions($cachedAnnotationReader, $driverChain);
        self::$em = EntityManager::create($this->getConnectionOptions(), $config, $evm);

        $this
            ->initFilters($config)
            ->initTypes($config);

        return $this;
    }

    /**
     * @return $this
     */
    protected function initTypes()
    {
        $typesConf = Application::getConfigManager()->loadForModule('Cmf\Db', 'type');

        foreach ($typesConf as $name => $className) {
            Type::addType($name, $className);
        }

        return $this;
    }

    /**
     * @param \Doctrine\ORM\Configuration $config
     * @return $this
     */
    protected function initFilters(Configuration $config)
    {
        $filtersConf = Application::getConfigManager()->loadForModule('Cmf\Db', 'filter');

        foreach ($filtersConf as $filterName => $itemConf) {
            $config->addFilter($filterName, $itemConf->class);
            self::$em->getFilters()->enable($filterName);
        }

        return $this;
    }

    /**
     * Load database connection options from config
     *
     * @return array
     */
    protected function getConnectionOptions()
    {
        $connectionConfig = Application::getConfigManager()->loadForModule('Cmf\Db', 'connection');

        return $connectionConfig->toArray();
    }

    /**
     * extension listeners
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param \Doctrine\ORM\Mapping\Driver\DriverChain $driverChain
     * @return \Doctrine\Common\EventManager
     */
    protected function initExtensions(\Doctrine\Common\Annotations\Reader $annotationReader, DriverChain $driverChain)
    {
        // load superclass metadata mapping only, into driver chain
        // also registers Gedmo annotations.NOTE: you can personalize it
        \Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $annotationReader);

        // create event manager and hook prefered extension listeners
        $evm = new \Doctrine\Common\EventManager();

        $eventsConf = Application::getConfigManager()->loadForModule('Cmf\Db', 'eventListener');

        foreach ($eventsConf as $itemConf) {
            $className = $itemConf->class;
            $listener = new $className();
            $listener->setAnnotationReader($annotationReader);
            $evm->addEventSubscriber($listener);
        }

        return $evm;
    }
}
