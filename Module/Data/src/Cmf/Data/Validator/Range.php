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
 * Range validator
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Range extends AbstractValidator
{
    const INVALID_MIN = 'invalidMin';
    const INVALID_MAX = 'invalidMax';

    /** @var bool|int|float */
    protected $minValue = false;

    /** @var bool|int|float */
    protected $maxValue = false;

    /**
     * @param int|float|bool $minValue
     * @param int|float|bool $maxValue
     * @throws Exception
     */
    public function __construct($minValue = false, $maxValue = false)
    {
        if (false !== $minValue && false !== $maxValue && $maxValue < $minValue) {
            throw new Exception('Incorrect min and max parameters');
        }

        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
    }

    /**
     * @param int|float $value
     * @return bool
     */
    public function isValid($value)
    {
        $result = true;

        if ($result && false !== $this->minValue && $this->minValue > $value) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID_MIN] = sprintf($lng[self::INVALID_MIN], $this->minValue);

            $result = false;
        }

        if ($result && false !== $this->maxValue && $this->maxValue < $value) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID_MAX] = sprintf($lng[self::INVALID_MAX], $this->maxValue);

            $result =  false;
        }

        return $result;
    }
}
