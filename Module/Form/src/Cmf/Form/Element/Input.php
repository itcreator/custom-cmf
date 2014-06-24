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
class Input extends AbstractElement
{
    /** @var string */
    protected $tagName = 'input';

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $this->filters->filter($value);
        $this->attributes->value = $this->value;
        $this->valueUnfiltered = $value;

        return $this;
    }
}
