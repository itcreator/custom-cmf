<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Permission\Listener;

use Cmf\Controller\Response\Forward;
use Cmf\Event\TSharedListenerAggregate;
use Cmf\System\Application;
use Cmf\User\Auth;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;

/**
 * Permission listener for CRUD controllers
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CrudAccess implements SharedListenerAggregateInterface
{
    use TSharedListenerAggregate;

    protected $identity = ['Cmf\Component\ActionLink\Collection'];

    /**
     * @param string $key
     * @param SharedEventManagerInterface $events
     * @param int $priority
     * @return $this
     */
    protected function attachListeners($key, SharedEventManagerInterface $events, $priority = 1)
    {
        $eventName = \Cmf\Component\ActionLink\Collection::EVENT_ACTION_LINK_GETTING_AFTER;
        $this->listeners[$key][] = $events->attach($this->identity, $eventName, [$this, 'afterLinks'], $priority);

        return $this;
    }

    /**
     * @param EventInterface $e
     * @return Forward
     */
    public function afterLinks(EventInterface $e)
    {
        $acl = Application::getAcl();
        $user = Application::getAuth()->getUser();

        $params = $e->getParams();

        $actionLinks = empty($params['actionLinks']) ? [] : $params['actionLinks'];
        $items = empty($actionLinks['items']) ? [] : $actionLinks['items'];

        foreach ($items as $name => $link) {
            $url = $link['url'];

            if (!$acl->actionIsAllowed($user, $url['module'], $url['controller'], $name)) {
                unset($items[$name]);
            }
        }

        $actionLinks['items'] = $items;

        return $actionLinks;
    }
}
