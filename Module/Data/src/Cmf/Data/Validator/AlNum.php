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
 * Alphabet and numbers validator
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class AlNum extends AbstractValidator
{
    const INVALID = 'invalid';
    const DIGITS = 'digits';
    const CYRILLIC_SYMBOLS = 'cyrillicSymbols';
    const LATIN_SYMBOLS = 'latinSymbols';
    const SYMBOLS = 'symbols';

    /** @var string */
    protected $additionalSymbols;

    /** @var bool */
    protected $digitsIsAllowed;

    /** @var bool */
    protected $latinIsAllowed;

    /** @var bool */
    protected $cyrillicIsAllowed;

    /**
     * @param bool $digitsIsAllowed
     * @param bool $latinIsAllowed
     * @param bool $cyrillicIsAllowed
     * @param string $additionalSymbols
     */
    public function __construct($digitsIsAllowed = true, $latinIsAllowed = true, $cyrillicIsAllowed = true, $additionalSymbols = '')
    {
        $this->latinIsAllowed = $latinIsAllowed;
        $this->digitsIsAllowed = $digitsIsAllowed;
        $this->additionalSymbols = $additionalSymbols;
        $this->cyrillicIsAllowed = $cyrillicIsAllowed;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $symbols = $this->digitsIsAllowed ? '0-9' : '';
        $symbols .= $this->latinIsAllowed ? 'a-zA-Z' : '';
        $symbols .= $this->cyrillicIsAllowed ? 'а-яА-ЯёЁ' : '';
        $symbols .= $this->additionalSymbols;

        if (!($result = preg_match('/^([' . $symbols . ']+)*$/u', $value))) {
            $lng = \Cmf\Language\Factory::get($this);

            $symbols = [];
            if ($this->digitsIsAllowed) {
                $symbols[] = $lng[self::DIGITS];
            }
            if ($this->latinIsAllowed) {
                $symbols[] = $lng[self::LATIN_SYMBOLS];
            }
            if ($this->cyrillicIsAllowed) {
                $symbols[] = $lng[self::CYRILLIC_SYMBOLS];
            }
            if ($this->additionalSymbols) {
                $symbols[] = sprintf($lng[self::SYMBOLS], $this->additionalSymbols);
            }

            $this->messages[self::INVALID] = sprintf($lng[self::INVALID], implode(', ', $symbols));
        }

        return (bool)$result;
    }
}
