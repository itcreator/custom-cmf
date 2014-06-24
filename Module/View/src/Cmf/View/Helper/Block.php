<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Helper;

use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Block extends AbstractHelper
{
    /**
     * @param string $containerName
     * @param array $params
     * @return string
     */
    public function renderContainer($containerName, array $params = [])
    {
        return Application::getBlockManager()->renderContainer($containerName, $params);
    }

    public function clear()
    {
        //TODO: refactor interface for removing of the method
        return $this;
    }
}
