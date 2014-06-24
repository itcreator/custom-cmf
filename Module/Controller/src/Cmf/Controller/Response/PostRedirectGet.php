<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Controller\Response;

use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class PostRedirectGet extends AbstractResponse
{
    /** @var array */
    protected $redirectPath = [];

    /**
     * @param array $redirectPath
     * @return PostRedirectGet
     */
    public function setRedirectPath(array $redirectPath)
    {
        $this->redirectPath = $redirectPath;

        return $this;
    }

    /**
     * @return Forward
     */
    public function handle()
    {
        $redirectPath = Application::getUrlBuilder()->build($this->redirectPath);
        $request = Application::getRequest();
        $request->clear();
        $request->set('path', $redirectPath);
        $forward = new Forward($this->controller);
        $forward->setForwardData('default', 'Redirect', 'Cmf\System');

        return $forward;
    }
}
