<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Validator;

use Cmf\Form\Element\AbstractElement;

/**
 * Confirmation validator
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Confirmation extends AbstractValidator
{
    const INVALID = 'invalid';

    /** @var AbstractElement */
    protected $element;

    /**
     * @param AbstractElement $element
     */
    public function __construct(AbstractElement $element)
    {
        $this->element = $element;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = $this->element->getValue() == $value;
        if (!$result) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID] = sprintf($lng[self::INVALID], $this->element->getTitle());
        }

        return $result;
    }
}
