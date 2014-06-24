<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Helper;

use Cmf\Structure\Collection\SimpleCollection;
use Cmf\System\Application;
use Cmf\System\Message;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class MessageBox extends AbstractHelper
{
    /** @var SimpleCollection */
    protected $messages;

    public function __construct()
    {
        $this->messages = new SimpleCollection();
    }

    /**
     * @param string $message
     * @param string $type
     * @return MessageBox
     */
    public function addMessage($message, $type = Message::TYPE_NOTIFICATION)
    {
        $this->messages->setItem(new Message($message, $type));

        return $this;
    }

    /**
     * @param string $message
     * @return MessageBox
     */
    public function addError($message)
    {
        $this->addMessage($message, Message::TYPE_NOTIFICATION);

        return $this;
    }

    /**
     * @param string $message
     * @return MessageBox
     */
    public function addSuccessMessage($message)
    {
        $this->addMessage($message, Message::TYPE_SUCCESS);

        return $this;
    }

    /**
     * @return MessageBox
     */
    public function clear()
    {
        $this->messages->clear();

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $view = Application::getViewProcessor();

        return $view->render($this, ['messages' => $this->messages]);
    }
}
