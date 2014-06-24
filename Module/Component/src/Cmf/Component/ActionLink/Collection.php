<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\ActionLink;

use Cmf\Event\TEventManagerAware;
use Cmf\Structure\Collection\LazyCollection;

use Zend\EventManager\EventManagerAwareInterface;

/**
 * Actions links collection
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Collection extends LazyCollection implements EventManagerAwareInterface
{
    use TEventManagerAware;

    const EVENT_ACTION_LINK_GETTING_AFTER = 'action_link_getting_after';
}
