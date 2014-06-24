<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Error\Controller;

use Cmf\Controller\AbstractController;
use Cmf\View\Helper\HelperFactory;

/**
 * Controller for error 403
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Error403Controller extends AbstractController
{
    public function defaultAction()
    {
        $lng = \Cmf\Language\Factory::get($this);
        $msg = sprintf('%s 403. %s.', $lng['error'], $lng['pageNotFound']);

        HelperFactory::getTitle()->setTitle($msg);
        HelperFactory::getMeta()->addTag('keywords', $msg);
        HelperFactory::getMeta()->addTag('description', $msg);

        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        header('Status: 403 Forbidden');
    }
}
