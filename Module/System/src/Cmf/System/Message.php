<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\System;

/**
 * Class form message alerts
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Message
{
    //todo: it is only for twitter bootstrap. refactor this constants
    const TYPE_NOTIFICATION = 'info';
    const TYPE_ERROR = 'danger';
    const TYPE_SUCCESS = 'success';

    /** @var int */
    protected $type;

    /** @var string */
    protected $message;

    /**
     * @param string $message
     * @param string $type
     */
    public function __construct($message, $type = self::TYPE_NOTIFICATION)
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
