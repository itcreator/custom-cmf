<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
namespace Cmf\Event;

use Zend\EventManager\SharedEventManagerInterface;

/**
 * Trait for SharedListenerAggregateInterface
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
trait TSharedListenerAggregate
{
    /** @var \Zend\Stdlib\CallbackHandler[][] */
    protected $listeners = [];

    /**
     * @param SharedEventManagerInterface $events
     * @return string
     */
    protected function getEventsKey(SharedEventManagerInterface $events)
    {
        return spl_object_hash($events);
    }

    /**
     * @param SharedEventManagerInterface $events
     * @param int $priority
     */
    public function attachShared(SharedEventManagerInterface $events, $priority = 1)
    {
        $key = $this->getEventsKey($events);

        if (empty($this->listeners[$key])) {
            $this->listeners[$key] = [];
        }

        $this->attachListeners($key, $events, $priority);
    }

    /**
     * @param string $key
     * @param SharedEventManagerInterface $events
     * @param int $priority
     * @return $this
     */
    protected function attachListeners($key, SharedEventManagerInterface $events, $priority = 1)
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        $key = $this->getEventsKey($events);
        if (empty($this->listeners[$key])) {
            return;
        }

        foreach ($this->listeners[$key] as $index => $callback) {
            foreach ($this->identity as $id) {
                if ($events->detach($id, $callback)) {
                    unset($this->listeners[$key][$index]);
                }
            }
        }
    }
}
