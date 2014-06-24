<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Validator;

use Cmf\Structure\Collection\SimpleCollection;
use Cmf\System\Message;

/**
 * Class for validators collection
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Collection extends SimpleCollection
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $isValid = true;
        $n = count($this->items);
        for ($i = 0; $i < $n; $i ++) {
            $isValid &= $this->items[$i]->isValid($value);
        }

        return (bool)$isValid;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $messages = new SimpleCollection();
        $n = count($this->items);
        for ($i = 0; $i < $n; $i ++) {
            foreach ($this->items[$i]->getMessages() as $msg) {
                $messages->setItem(new Message($msg, Message::TYPE_ERROR));
            }
        }

        return $messages;
    }
}
