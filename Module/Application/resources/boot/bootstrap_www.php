<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 * @author    Vital Leshchyk <vitalleshchyk@gmail.com>
 */

require 'bootstrap.php';

if ("development" == APPLICATION_MODE) {
    function dmp($variable, $caption = 'dump')
    {
    }
} else {
    function dmp($variable, $caption = 'dump')
    {
    }
}

if (PHP_SESSION_NONE === session_status()) {
    session_start();
}
