<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Filter;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Trim implements FilterInterface
{
    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return trim($value);
    }
}
