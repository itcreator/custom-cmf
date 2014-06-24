<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

/**
 * Trait for EventManagerAwareInterface
 * 
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
trait TEventManagerAware
{
    /** @var EventManagerInterface */
    protected $events;

    /**
     * @param EventManagerInterface $events
     * @return $this
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $currentClass = get_called_class();
        $classes = [];
        while (__CLASS__ != $currentClass) {
            $classes[] = $currentClass;
            $currentClass = get_parent_class($currentClass);
        }
        $classes[] = __CLASS__;

        $events->setIdentifiers($classes);
        $this->events = $events;

        return $this;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }

    /**
     * @return \Zend\EventManager\SharedEventManager
     */
    public function getSharedEventManager()
    {
        return $this->getEventManager()->getSharedManager();
    }
}
