<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 * @author    Vital Leshchyk <vitalleshchyk@gmail.com>
 */

if ("development" == APPLICATION_MODE) {
    function dmp($variable, $caption = 'dump')
    {
        \FB::group($caption . ' |  type: ' . gettype($variable));
        \FB::info($variable);
        //\FB::trace('trace');
        \FB::groupEnd();
    }
} else {
    function dmp($variable, $caption = 'dump')
    {
    }
}

session_start();
