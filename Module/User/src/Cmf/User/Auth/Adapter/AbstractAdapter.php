<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Auth\Adapter;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    protected $messages = [];

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
