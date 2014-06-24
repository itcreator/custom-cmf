<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Table\Row;

/**
 * Array adapter for rows
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ArrayAdapter extends A
{
    /** @var array */
    protected $dataProvider;

    /**
     * @param string $key
     * @return string
     */
    public function getRawField($key)
    {
        return isset($this->dataProvider[$key]) ? $this->dataProvider[$key] : null;
    }
}
