<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Form\Handler;

use Cmf\Controller\MvcRequest;
use Cmf\Form\Handler\AbstractHandler;
use Cmf\System\Application;
use Cmf\User\Auth;

/**
 * Factory for comment form
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class HandlerFactory
{
    /**
     * @param MvcRequest $mvcRequest
     * @param array $formParams
     * @param array $handlerParams
     * @return AbstractHandler|AuthorizedHandler
     */
    public static function create(MvcRequest $mvcRequest, $formParams = [], $handlerParams = [])
    {
        $request = Application::getRequest();
        $defaultFormParams = [
            'action'=> Application::getUrlBuilder()->build([
                'module' => $mvcRequest->getModuleName(),
                'controller' => 'Comment',
                'action' => 'create',
                'idContent' => (int)$request->get('idContent'),
                'idParent' => (int)$request->get('idParent'),
            ])
        ];

        $formParams = array_merge($defaultFormParams, $formParams);

        if (Application::getAuth()->getUser()->isGuest()) {
            $form = new GuestHandler($formParams, $handlerParams);
        } else {
            $form = new AuthorizedHandler($formParams, $handlerParams);
        }

        return $form;
    }
}
