<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Filter;

use Cmf\Structure\Collection\SimpleCollection;

/**
 * Filter collection
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Collection extends SimpleCollection
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        $n = count($this->items);
        for ($i = 0; $i < $n; $i++) {
            $value = $this->items[$i]->filter($value);
        }

        return $value;
    }
}
