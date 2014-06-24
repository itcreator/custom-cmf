<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Block\Response;

use Cmf\System\Application;

/**
 * Response for rendering of a block
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Html extends AbstractResponse
{
    /**
     * @return string
     */
    public function handle()
    {
        $view = Application::getViewProcessor();

        return $view->render($this->block, $this->params);
    }
}
