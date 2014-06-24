<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\PublicResources\Composer;

use Cmf\PublicResources\Assets;
use Composer\Script\CommandEvent;

/**
 * Post scripts for Composer
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Script
{
    /**
     * @param CommandEvent $event
     */
    public static function installAssets(CommandEvent $event)
    {
        $assets = new Assets();
        $assets->install();
    }
}
