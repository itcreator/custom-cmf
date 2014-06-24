<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Module\Exception;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ModuleNotFoundException extends \Cmf\System\Exception
{
    public function __construct($message = "Module not found", $code = 0, \Exception $previous = null)
    {
    }
}
