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
class Html extends AbstractResponse
{
    /**
     * @return mixed
     */
    public function getContent()
    {
        $action = strtolower($this->controller->getActionName());
        $template = '/action/' . $action;
        $view = Application::getViewProcessor();

        return $view->render($this->controller, $this->renderData, $template);
    }

    /**
     * @return string
     */
    public function handle()
    {
        $view = Application::getViewProcessor();
        $layout = $view->render($this, ['content' =>$this->getContent()], '/Layout');
        $result = $view->render($this, ['layout' => $layout]);

        return $result;
    }
}
