<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Form;

use Cmf\Controller\MvcRequest;
use Cmf\User\Auth;
use Cmf\System\Application;

/**
 * Form factory for comments
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    /**
     * @param MvcRequest $request
     * @param array $params
     * @return AuthorizedForm|GuestForm
     */
    public static function create(MvcRequest $request, $params = [])
    {
        $params = array_merge([
            'idContent' => null,
            'idParent' => null,
        ], $params);

        $params['action'] = Application::getUrlBuilder()->build([
            'module' => $request->getModuleName(),
            'controller' => 'Comment',
            'action' => 'create',
            'idContent' => $params['idContent'],
            'idParent' => $params['idParent'],
        ]);

        if (Application::getAuth()->getUser()->isGuest()) {
            $form = new GuestForm($params);
        } else {
            $form = new AuthorizedForm($params);
        }

        return $form;
    }
}
