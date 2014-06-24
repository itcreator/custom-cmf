<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 * @author    Vital Leshchyk <vitalleshchyk@gmail.com>
 */

$root = realpath($_SERVER['DOCUMENT_ROOT'] . '/../');
define('ROOT', (DIRECTORY_SEPARATOR == $root[strlen($root) - 1]) ? $root : $root . DIRECTORY_SEPARATOR);
chdir(ROOT);

// Define application environment
if (!defined('APPLICATION_MODE')) {
    define('APPLICATION_MODE', (getenv('APPLICATION_MODE') ? getenv('APPLICATION_MODE') : 'production'));
}

require 'boot/bootstrap.php';
require 'boot/bootstrap_www.php';

//TODO: move to config
//Timezone
date_default_timezone_set('Europe/Minsk');

$application = \Cmf\System\Application::getInstance();
$application->init()->start();
