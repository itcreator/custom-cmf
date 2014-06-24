<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Captcha\Validator;

use Cmf\Data\Validator\AbstractValidator;

/**
 * captcha validation
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Captcha extends AbstractValidator
{
    const INVALID = 'invalid';

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $captcha = new \Cmf\Captcha\Captcha();
        if ($captcha->loadValueFromSession()->getValue() == $value) {
            return true;
        } else {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID] = $lng[self::INVALID];
            return false;
        }
    }
}
