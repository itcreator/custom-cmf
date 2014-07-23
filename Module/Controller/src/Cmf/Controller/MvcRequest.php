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
use Cmf\Controller\Response\Html;
use Cmf\Error\Controller\Error404Controller;
use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class MvcRequest
{
    /** @var AbstractController */
    protected $controller;

    /** @var string */
    protected $moduleName;

    /** @var string */
    protected $controllerName = '';

    /** @var string */
    protected $actionName = 'default';

    /**
     * @param string $moduleName
     * @param string $controllerName
     * @param string | null $actionName
     */
    public function __construct($moduleName, $controllerName, $actionName = null)
    {
        $this->moduleName = $moduleName;
        $this->controllerName = ucfirst(strtolower($controllerName));
        $this->actionName = strtolower($actionName);
    }

    /**
     * @return MvcRequest
     */
    protected function createController()
    {
        $loader = Application::getClassLoader();
        $controllerClass = sprintf('%s\\Controller\\%sController', $this->moduleName, $this->controllerName);

        if ($loader->findFile($controllerClass)) {
            $this->controller = new $controllerClass($this);
        } else {
            $this->moduleName = 'Cmf\Error';
            $this->controllerName = 'Error404';
            $this->actionName = 'default';
            $this->controller = new Error404Controller($this);
        }

        return $this;
    }

    /**
     * @return AbstractResponse
     * @throws Exception
     */
    public function send()
    {
        $result = $this->getController()->runAction($this->actionName);
        $response = null;

        if ($result instanceof AbstractResponse) {
            $response = $result;
        } elseif (is_array($result)) {
            $response = new Html($this->getController(), $result);
        } elseif (!$result) {
            $response = new Html($this->getController());
        } else {
            throw new Exception('Incorrect mvc response type');
        }

        return $response;
    }

    /**
     * @return AbstractController
     */
    public function getController()
    {
        if (!$this->controller) {
            $this->createController();
        }

        return $this->controller;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        //TODO: delete this method after creating of a module classes
        return $this->moduleName;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        //TODO: delete this method after creating of a module classes ???
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return string
     */
    public function getActionKey()
    {
        return sprintf('%s:%s:%s', $this->getModuleName(), $this->getControllerName(), $this->getActionName());
    }
}
