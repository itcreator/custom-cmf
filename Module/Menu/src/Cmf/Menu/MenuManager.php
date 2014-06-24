<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Menu;

use Cmf\System\Application;
use Cmf\View\Helper\HelperFactory;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class MenuManager
{

    public function getMenu($menuName)
    {
        HelperFactory::getStyle()->addStyle('css/menu.css', 'Cmf\Menu');

        $cm = Application::getConfigManager();
        $config = $cm->loadForModule('Cmf\Menu', 'menu');

        return new Menu($config->$menuName);
    }
}
