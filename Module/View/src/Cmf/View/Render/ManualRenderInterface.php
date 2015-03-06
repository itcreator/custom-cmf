<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2015 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Render;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
interface ManualRenderInterface
{
    /**
     * @return string
     */
    public function render();
}
