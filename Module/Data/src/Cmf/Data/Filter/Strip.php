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
 * This filter optimises blanks symbols
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Strip implements FilterInterface
{
    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return preg_replace('/\s+/u', ' ', (string)$value);
    }
}
