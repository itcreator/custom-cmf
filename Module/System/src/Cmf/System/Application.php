<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\System;

use Cmf\Block\BlockManager;
use Cmf\Config\ConfigManager;
use Cmf\Controller\MvcRequest;
use Cmf\Controller\Response\AbstractResponse;
use Cmf\Event\TEventManagerAware;
use Cmf\Form\FormDataMapper;
use Cmf\Menu\MenuManager;
use Cmf\Module\ModuleManager;
use Cmf\Permission\Acl;
use Cmf\Standard\TSingleton;
use Cmf\Url\UrlBuilder;

use Cmf\User\Auth;
use Cmf\View\ViewProcessor;
use Composer\Autoload\ClassLoader;
use Doctrine\ORM\EntityManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class Application
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @method static Acl getAcl() return service Acl
 * @method static Auth getAuth() return service Auth
 * @method static BlockManager getBlockManager() return service BlockManager
 * @method static ClassLoader getClassLoader() return service ClassLoader
 * @method static ConfigManager getConfigManager() return service ConfigManager
 * @method static EntityManager getEntityManager() return service EntityManager
 * @method static FormDataMapper getFormDataMapper() return service FormDataMapper
 * @method static MenuManager getMenuManager() return service MenuManager
 * @method static ModuleManager getModuleManager() return service ModuleManager
 * @method static Request getRequest() return service Request
 * @method static UrlBuilder getUrlBuilder() return service UrlBuilder
 * @method static ViewProcessor getViewProcessor() return service ViewProcessor

 */
class Application implements EventManagerAwareInterface
{
    use TSingleton;
    use TEventManagerAware;

    /** @var ServiceManager */
    protected static $serviceManager;

    /** @var \Cmf\Controller\MvcRequest */
    protected static $mvcRequest;

    /**
     * Redirect from www.hostname to hostname
     *
     * @return Application
     */
    public function killWww()
    {
        $host = $_SERVER['HTTP_HOST'];
        if (stripos($host, 'www.') === 0) {
            $host = substr_replace($host, '', 0, 4);
            $uri = $_SERVER['REQUEST_URI'];
            header("HTTP/1.1 301 Moved Permanently");

            if ('/' == $uri[0]) {
                header('Location: http://' . $host . $uri);
            } else {
                header('Location: http://' . $host . '/' . $uri);
            }

            die;
        }

        return $this;
    }

    /**
     * @return Application
     */
    public function dispatch()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\System', 'module');
        $moduleName = self::getRequest()->get('module', Request::TYPE_GET, $config->defaultModule);
        $controllerName = self::getRequest()->get('controller', Request::TYPE_GET, $config->defaultController);
        $actionName = self::getRequest()->get('action', Request::TYPE_GET, $config->defaultAction);

        self::$mvcRequest = new MvcRequest($moduleName, $controllerName, $actionName);
        $response = self::$mvcRequest->send();

        //While response is not ready for displaying
        while (1) {
            $result = $response->handle();
            if ($result instanceof MvcRequest) {
                self::$mvcRequest = $result;
                $response = self::$mvcRequest->send();
            } elseif ($result instanceof AbstractResponse) {
                $response = $result;
            } else {
                break;
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function initEvents()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\System', 'event');

        $listeners = [];
        if ($config instanceof \Zend\Config\Config && $config->listener instanceof \Zend\Config\Config) {
            $listeners = empty($config->listener[0]) ? [$config->listener] : $config->listener;
        }

        $evm = $this->getSharedEventManager();
        foreach ($listeners as $listener) {
            $className = $listener->class;
            $evm->attachAggregate(new $className());
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function init()
    {
        ob_start();
        $this->initServiceManager();
        $this->initEvents();

        return $this;
    }

    /**
     * @return Application
     */
    public function start()
    {
        $this->killWww()->dispatch();

        return $this;
    }

    /**
     * @return $this
     */
    protected function initServiceManager()
    {
        self::$serviceManager = new ServiceManager();
        self::$serviceManager->setAllowOverride(true);

        self::$serviceManager->setService('ClassLoader', require ('vendor/autoload.php'));

        self::$serviceManager->setFactory('Acl', function () {
            return new Acl();
        });

        self::$serviceManager->setFactory('Auth', function () {
            return new Auth();
        });

        self::$serviceManager->setFactory('BlockManager', function () {
            return new BlockManager();
        });

        self::$serviceManager->setFactory('EntityManager', function () {
            $db = new \Cmf\Db\Doctrine();

            return $db->getEm();
        });

        self::$serviceManager->setFactory('ConfigManager', function () {
            return new ConfigManager();
        });

        self::$serviceManager->setFactory('FormDataMapper', function () {
            return new FormDataMapper();
        });

        self::$serviceManager->setFactory('MenuManager', function () {
            return new MenuManager();
        });

        self::$serviceManager->setFactory('ModuleManager', function () {
            return new ModuleManager();
        });

        self::$serviceManager->setFactory('Request', function () {
            return new Request();
        });

        self::$serviceManager->setFactory('UrlBuilder', function () {
            return new UrlBuilder();
        });

        self::$serviceManager->setFactory('ViewProcessor', function () {
            return new ViewProcessor();
        });
    }

    /**
     * @return $this
     */
    public function resetEntityManager()
    {
        self::$serviceManager->setFactory('EntityManager', function () {
            $db = new \Cmf\Db\Doctrine();

            return $db->getEm(true);
        });

        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return self::$serviceManager;
    }

    /**
     * Facade for ServiceManager::get
     *
     * @param string $serviceName
     * @return array|object
     */
    public static function get($serviceName)
    {
        return self::$serviceManager->get($serviceName);
    }

    /**
     * @return MvcRequest
     */
    public static function getMvcRequest()
    {
        return self::$mvcRequest;
    }

    /**
     * Facade for service manager
     *
     * @param string $name
     * @param array $arguments
     * @return array|null|object
     */
    public static function __callStatic($name, $arguments)
    {
        $service = null;
        if ('get' == substr($name, 0, 3)) {
            $key = substr($name, 3);
            $service = self::get($key);
        }

        return $service;
    }
}
