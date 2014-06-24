<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Controller;

use Cmf\Controller\Response\AbstractResponse;
use Cmf\Controller\Response\Forward;
use Cmf\Event\TEventManagerAware;
use Cmf\System\Application;

use Cmf\View\Helper\HelperFactory;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class AbstractController implements EventManagerAwareInterface
{
    use TEventManagerAware;

    const EVENT_ACTION_AFTER = 'controller_action_after';
    const EVENT_ACTION_BEFORE = 'controller_action_before';

    /** @var MvcRequest */
    protected $request;

    /** @var string */
    protected $actionName;

    /** @var mixed */
    protected $data = null;

    /** @var string|null path to layout file */
    protected $layoutPath = null;

    /** * @var \Zend\Config\Config */
    protected $config;

    /**
     * @param MvcRequest $request
     */
    public function __construct(MvcRequest $request)
    {
        $this->request = $request;
        $this->config = Application::getConfigManager()->loadForModule($request->getModuleName());
    }

    /**
     * @param string $methodName
     * @param array $args
     * @return Forward
     * @throws Exception
     */
    public function __call($methodName, $args)
    {
        if ('Action' == substr($methodName, -6)) {
            return $this->forward404();
        }

        throw new Exception(sprintf('Method "%s" does not exist', $methodName));
    }

    /**
     * @param string $actionName
     * @return null | AbstractResponse
     */
    public function runAction($actionName)
    {
        $this->actionName = $actionName;
        $action = $actionName . 'Action';

        $evm = $this->getEventManager();

        $result = null;

        $arguments = ['controller' => $this];
        $evm->trigger(self::EVENT_ACTION_BEFORE, $this, $arguments, function ($response) use (&$result) {
            if ($response instanceof AbstractResponse) {
                $result = $response;
            }
        });

        if (false == $result instanceof AbstractResponse) {
            $result = $this->$action();
            $evm->trigger(self::EVENT_ACTION_AFTER, $this);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return \Zend\Config\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return MvcRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        $arr = explode('\\', get_class($this));

        $className = strtolower(array_pop($arr));

        return substr($className, 0, -10);
    }

    /**
     * @return \Doctrine\ORM\EntityManager|null
     */
    public function getEntityManager()
    {
        return Application::getEntityManager();
    }

    /**
     * @param string $action
     * @param string|null $controller
     * @param string|null $module
     * @return Forward
     */
    public function forward($action, $controller = null, $module = null)
    {
        if (!$controller) {
            $controller = $this->request->getControllerName();
        }
        if (!$module) {
            $module = $this->request->getModuleName();
        }
        $forward = new Forward($this);
        $forward->setForwardData($action, $controller, $module);
        return $forward;
    }

    /**
     * @param string $errorMessage
     * @return Forward
     */
    public function forward404($errorMessage = '')
    {
        if ($errorMessage) {
            HelperFactory::getMessageBox()->addError($errorMessage);
        }
        $forward = new Forward($this);
        $forward->setForwardData('default', 'Error404', 'Cmf\Error');

        return $forward;
    }
}
