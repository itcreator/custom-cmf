<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 * @author    Vital Leshchyk <vitalleshchyk@gmail.com>
 */

function initEvents()
{
    $evm = \Zend\EventManager\GlobalEventManager::getEventCollection();
    $evm->setSharedManager(new \Zend\EventManager\SharedEventManager());
}

// Define application environment
if (!defined('APPLICATION_MODE')) {
    define('APPLICATION_MODE', (getenv('APPLICATION_MODE') ? getenv('APPLICATION_MODE') : 'production'));
}

// TODO: use constants
if (APPLICATION_MODE == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
}

mb_internal_encoding('UTF-8');

require_once ROOT . '/vendor/autoload.php';

initEvents();
