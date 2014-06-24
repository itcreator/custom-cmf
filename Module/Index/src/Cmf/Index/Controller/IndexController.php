<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Index\Controller;

use Cmf\Controller\AbstractController;
use Cmf\Controller\MvcRequest;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class IndexController extends AbstractController
{
    public function __construct(MvcRequest $request)
    {
        parent ::__construct($request);
    }

    public function defaultAction()
    {
        return [];
    }
}
