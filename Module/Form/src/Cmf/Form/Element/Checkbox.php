<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Element;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Checkbox extends Input
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->attributes->type = 'checkbox';
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = (bool)$value;
        $this->valueUnfiltered = (bool)$value;
        if ($this->value) {
            $this->attributes->checked = 'checked';
        } else {
            $this->attributes->offsetUnset('checked');
        }

        return $this;
    }
}
