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
 * Email validator
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Email extends AbstractValidator
{
    const INVALID = 'invalid';

    public function isValid($value)
    {
        if (!($result = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $value))) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID] = $lng[self::INVALID];
        }

        return $result;
    }
}
