<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Filter;

/**
 * Filter for digits
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Digits implements FilterInterface
{
    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return preg_replace('/[^0-9]/', '', (string)$value);
    }
}
