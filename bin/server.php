<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

$root = realpath($_SERVER['DOCUMENT_ROOT'] . '/..');
define('ROOT', (DIRECTORY_SEPARATOR == $root[strlen($root) - 1]) ? $root : $root . DIRECTORY_SEPARATOR);
chdir(ROOT);

require 'boot/bootstrap_www.php';

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$requestedPath = PUBLIC_DIR . $uri;

if ('/' !== $uri && file_exists($requestedPath)) {
    return false;
}

//TODO: move to config
//Timezone
date_default_timezone_set('Europe/Minsk');

$application = \Cmf\System\Application::getInstance();
$application->init()->start();
