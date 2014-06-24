<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */


namespace Cmf\User\Controller;

use Cmf\System\Application;
use Cmf\Controller\AbstractController;
use Cmf\Controller\Response\PostRedirectGet;

use Cmf\User\Auth;
use Cmf\User\Auth\Adapter\DoctrineTable;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class LoginController extends AbstractController
{
    /**
     * @return array|PostRedirectGet
     */
    public function defaultAction()
    {
        $response = null;
        $loginForm = new \Cmf\User\Form\Login();
        if ($loginForm->isSubmitted()) {
            $loginForm->getValuesFromRequest();
            if ($loginForm->validate()) {
                $em = Application::getEntityManager();
                $adapter = new DoctrineTable($em, null, 'userName');
                $adapter
                    ->setLogin($loginForm->getElement('login')->getValue())
                    ->setPassword($loginForm->getElement('password')->getValue());

                $auth = Application::getAuth();
                $auth->setRememberUser($loginForm->getElement('rememberUser')->getValue());
                if ($auth->authenticate($adapter)) {
                    $config = Application::getConfigManager()->loadForModule('Cmf\System', 'module');
                    $response = new PostRedirectGet($this);
                    $response->setRedirectPath([
                        'module' => $config->defaultModule,
                    ]);
                } else {
                    $loginForm->getMessages()->setItem($auth->getMessage());
                }
            }
        }

        if (!$response) {
            $response = ['form' => $loginForm];
        }

        return $response;
    }

    /**
     * @return PostRedirectGet
     */
    public function logoutAction()
    {
        Application::getAuth()->logout();

        $config = Application::getConfigManager()->loadForModule('Cmf\System', 'module');
        $response = new PostRedirectGet($this);
        $response->setRedirectPath([
            'module' => $config->defaultModule,
        ]);

        return $response;
    }
}
