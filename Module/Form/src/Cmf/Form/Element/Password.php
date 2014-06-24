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
class Password extends Input
{
    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->attributes->type = 'password';
    }

    /**
     * @param mixed $value
     * @return A
     */
    public function setValue($value)
    {
        $this->value = $this->filters->filter($value);
        $this->valueUnfiltered = $value;

        return $this;
    }
}
