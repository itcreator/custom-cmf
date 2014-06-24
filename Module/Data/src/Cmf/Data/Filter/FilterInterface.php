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
 * Interface for filters
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
interface FilterInterface
{
    /**
     * @abstract
     * @param mixed $value
     * @return mixed
     */
    public function filter($value);
}
