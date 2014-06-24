<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Permission;

use Cmf\Event\TEventManagerAware;
use Cmf\System\Application;
use Cmf\User\Model\Entity\User;

use Zend\Config\Config as ZendConfig;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Acl extends \Zend\Permissions\Acl\Acl
{
    use TEventManagerAware;

    const EVENT_INIT_ROLES_AFTER = 'permission_init_roles_after';
    const EVENT_INIT_ROLES_BEFORE = 'permission_init_roles_before';

    const EVENT_CHECK_PERMISSIONS_BEFORE = 'permission_check_permissions_before';

    const ROLE_ADMIN = 'admin';
    const ROLE_GUEST = 'guest';
    const ROLE_USER = 'user';

    //	protected $initializedForModules = [];

    public function __construct()
    {
        $this->initRoles();
    }

    /**
     * @return $this
     */
    protected function initRoles()
    {
        $evm = $this->getEventManager();
        $evm->trigger(self::EVENT_INIT_ROLES_BEFORE, $this);

        $guestRole = new Role(self::ROLE_GUEST);
        $this->addRole($guestRole);

        $userRole = new Role(self::ROLE_USER);
        $this->addRole($userRole);

        $adminRole = new Role(self::ROLE_ADMIN);
        $this->addRole($adminRole, $userRole);

        $evm->trigger(self::EVENT_INIT_ROLES_AFTER, $this);

        return $this;
    }

    /**
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @return $this
     */
    protected function initModuleResources($moduleName, $controllerName, $actionName)
    {
        $moduleResource = new Resource(sprintf('module_%s', $moduleName));
        if (!$this->hasResource($moduleResource)) {
            $this->addResource($moduleResource);
        }

        $controllerResource = new Resource(sprintf('module_%s_controller_%s', $moduleName, $controllerName));
        if (!$this->hasResource($controllerResource)) {
            $this->addResource($controllerResource, $moduleResource);
        }

        $resourceName = sprintf('module_%s_controller_%s_action_%s', $moduleName, $controllerName, $actionName);
        $actionResource = new Resource($resourceName);
        if (!$this->hasResource($actionResource)) {
            $this->addResource($actionResource, $controllerResource);
        }

        return $this;
    }

    /**
     * @param \Zend\Config\Config $config
     * @param string $resourceName
     * @return $this
     */
    protected function addPermissionsByConfig(ZendConfig $config, $resourceName)
    {
        if (!$config->permission) {
            return $this;
        }

        $permissions = empty($config->permission[0]) ? [$config->permission] : $config->permission;
        foreach ($permissions as $permission) {
            $role = $permission->role ? $permission->role : null;
            if ('allow' == $permission->type) {
                $this->allow($role, $resourceName);
            } elseif ('deny' == $permission->type) {
                $this->deny($role, $resourceName);
            }
        }

        return $this;
    }

    /**
     * @param \Zend\Config\Config $config
     * @param string $levelName
     * @param string $levelItemName
     * @param string $resourceName
     * @return \Zend\Config\Config|null
     */
    protected function addPermissionForLevel(ZendConfig $config, $levelName, $levelItemName, $resourceName)
    {
        if ($config->$levelName instanceof ZendConfig && $config->$levelName->$levelItemName) {
            $itemConfig = $config->$levelName->$levelItemName;
            if ($itemConfig instanceof ZendConfig) {
                $this->addPermissionsByConfig($itemConfig, $resourceName);
            }
        } else {
            $itemConfig = null;
        }

        return $itemConfig;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function currentActionIsAllowed(User $user)
    {
        $request = Application::getInstance()->getMvcRequest();
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        return $this->actionIsAllowed($user, $moduleName, $controllerName, $actionName);

    }

    /**
     * @param User $user
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @return bool
     */
    public function actionIsAllowed(User $user, $moduleName, $controllerName, $actionName)
    {
        $moduleName = str_replace('\\', '-', $moduleName);
        $evm = $this->getEventManager();
        $evm->trigger(self::EVENT_CHECK_PERMISSIONS_BEFORE, $this);

        $this->initModuleResources($moduleName, $controllerName, $actionName);

        $config = Application::getConfigManager()->loadForModule('Cmf\Permission', 'acl');
        $resourceName = sprintf('module_%s', $moduleName);
        if ($moduleConfig = $this->addPermissionForLevel($config, 'module', $moduleName, $resourceName)) {

            $resourceName = sprintf('module_%s_controller_%s', $moduleName, $controllerName);
            if ($controllerConfig = $this->addPermissionForLevel($moduleConfig, 'controller', $controllerName, $resourceName)) {

                $resourceName = sprintf('module_%s_controller_%s_action_%s', $moduleName, $controllerName, $actionName);
                $this->addPermissionForLevel($controllerConfig, 'action', $actionName, $resourceName);
            }
        }

        $resourceName = sprintf('module_%s_controller_%s_action_%s', $moduleName, $controllerName, $actionName);

        $allowed = false;
        foreach ($user->getRoles() as $role) {
            if ($this->isAllowed($role->getName(), $resourceName)) {
                $allowed = true;
                break;
            }
        }

        return $allowed;
    }
}
