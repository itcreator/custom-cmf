<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Validator;

/**
 * String length validator
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class StrLen extends AbstractValidator
{
    const INVALID = 'invalid';

    /** @var int */
    protected $minLength = 0;

    /** @var int|null */
    protected $maxLength = 0;

    /**
     * @throws Exception
     * @param int $minLength
     * @param int|null $maxLength
     */
    public function __construct($minLength, $maxLength = null)
    {

        if ($maxLength < 0 || $minLength < 0 || $maxLength < $minLength) {
            throw new Exception('Incorrect min and max parameters');
        }

        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = (mb_strlen($value) >= $this->minLength && mb_strlen($value) <= $this->maxLength);
        if (!($result)) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID] = sprintf($lng[self::INVALID], $this->minLength, $this->maxLength);
        };

        return $result;
    }
}
