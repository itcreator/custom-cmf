<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\System\Controller;

use Cmf\Controller\AbstractController;
use Cmf\System\Application;
use Cmf\View\Helper\HelperFactory;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class RedirectController extends AbstractController
{
    /**
     * This method display redirect page
     *
     * @return array
     */
    public function defaultAction()
    {
        $lng = \Cmf\Language\Factory::get($this);
        HelperFactory::getMeta()->clear();
        HelperFactory::getJS()->clear();
        HelperFactory::getStyle()->clear();
        HelperFactory::getTitle()->setTitle('Redirect');
        if ($path = urldecode(Application::getRequest()->get('path'))) {
            HelperFactory::getMeta()->addTag('refresh', '0; url=' . $path);
            $response = ['path' => $path];
        } else {
            $response = ['error' => $lng['error_badPath']];
        }

        return $response;
    }
}
