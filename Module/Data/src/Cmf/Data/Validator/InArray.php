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
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class InArray extends AbstractValidator
{
    const INVALID = 'invalid';

    /** @var array */
    protected $values;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!($result = in_array($value, $this->values))) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID] = $lng[self::INVALID];
        }

        return $result;
    }
}
