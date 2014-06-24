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
 * Real path filter
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class RealPath implements FilterInterface
{

    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return realpath((string)$value);
    }
}
