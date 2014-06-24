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
 * Interface for validators
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
interface ValidatorInterface
{
    /**
     * @abstract
     * @param  $value
     * @return bool
     */
    public function isValid($value);

    /**
     * @abstract
     * @return array
     */
    public function getMessages();
}
