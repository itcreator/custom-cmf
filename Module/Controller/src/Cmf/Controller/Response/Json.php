<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Controller\Response;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Json extends AbstractResponse
{
    /**
     * @return string
     */
    public function handle()
    {
        header('Content-type: application/json');

        return json_encode($this->renderData);
    }
}
