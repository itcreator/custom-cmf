<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Menu\Item;

use Cmf\User\Auth;
use Cmf\Menu\Menu;
use Cmf\Menu\SubMenu;

use Cmf\System\Application;
use Zend\Config\Config as ZendConfig;

/**
 * Menu item factory
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    /**
     * @param ZendConfig $config
     * @param Menu|SubMenu $parent
     * @return AbstractItem
     */
    public static function create(ZendConfig $config, Menu $parent)
    {
        if ('false' === $config->enabled || '0' === $config->enabled || !self::checkPermissions($config)) {
            $item = null;
        } else {
            $type = $config->type ? $config->type : 'Link';
            $className = sprintf("Cmf\\Menu\\Item\\%s", $type);

            $item = new $className($config, $parent);
        }

        return $item;
    }

    /**
     * @param ZendConfig $config
     * @return bool
     */
    protected static function checkPermissions(ZendConfig $config)
    {
        $user = Application::getAuth()->getUser();

        if ($config->url instanceof ZendConfig) {
            $moduleConfig = Application::getConfigManager()->loadForModule('Cmf\System', 'module');
            $module = $config->url->module ? $config->url->module : $moduleConfig->defaultModule;
            $controller = $config->url->controller ? $config->url->controller : $moduleConfig->defaultController;
            $action = $config->url->action ? $config->url->action : $moduleConfig->defaultAction;

            $result = Application::getAcl()->actionIsAllowed($user, $module, $controller, $action);
        } else {
            $result = true;
        }

        return $result;
    }
}
