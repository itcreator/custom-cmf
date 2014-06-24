<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Block;

use Cmf\Block\AbstractBlock;
use Cmf\User\Auth;
use Cmf\System\Application;

/**
 * Block for login form or user info.
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class AuthBlock extends AbstractBlock
{
    /**
     * @return array
     */
    public function handle()
    {
        $ub = Application::getUrlBuilder();

        return [
            'user' => Application::getAuth()->getUser(),
            'loginUrl' => $ub->build(['module' => 'Cmf\User', 'controller' => 'login']),
            'logoutUrl' => $ub->build(['module' => 'Cmf\User', 'controller' => 'login', 'action' => 'logout']),
            'registerUrl' => $ub->build(['module' => 'Cmf\User', 'controller' => 'register']),
        ];
    }
}
