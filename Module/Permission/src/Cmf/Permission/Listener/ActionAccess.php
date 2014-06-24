<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Permission\Listener;

use Cmf\Controller\AbstractController;
use Cmf\Controller\Response\Forward;
use Cmf\Event\TSharedListenerAggregate;
use Cmf\System\Application;
use Cmf\User\Auth;
use Cmf\User\Controller\LoginController;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;

/**
 * Permission listener for modules, controllers and actions
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ActionAccess implements SharedListenerAggregateInterface
{
    use TSharedListenerAggregate;

    protected $identity = ['Cmf\Controller\AbstractController'];

    /**
     * @param string $key
     * @param SharedEventManagerInterface $events
     * @param int $priority
     * @return $this
     */
    protected function attachListeners($key, SharedEventManagerInterface $events, $priority = 1)
    {
        $eventName = AbstractController::EVENT_ACTION_BEFORE;
        $this->listeners[$key][] = $events->attach($this->identity, $eventName, [$this, 'before'], $priority);

        return $this;
    }

    /**
     * @param EventInterface $e
     * @return Forward|null
     */
    public function before(EventInterface $e)
    {
        $user = Application::getAuth()->getUser();

        $response = null;
        if (!Application::getAcl()->currentActionIsAllowed($user)) {
            $params = $e->getParams();
            /** @var AbstractController $controller */
            $controller = $params['controller'];
            if ($user->isGuest()) {
                if (!($controller instanceof LoginController)) {
                    $response = new Forward($controller);
                    $response->setForwardData('default', 'login', 'user');
                }
            } else {
                if ($controller->getControllerName() != 'error403') {
                    $response = new Forward($controller);
                    $response->setForwardData('default', 'error403', 'error');
                }
            }
        }

        return $response;
    }
}
