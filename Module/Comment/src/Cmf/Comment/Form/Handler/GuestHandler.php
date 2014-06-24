<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */


namespace Cmf\Comment\Form\Handler;

use Cmf\Comment\Form\GuestForm;
use Cmf\Comment\Model\Entity\Comment;

/**
 * Comment form handler for guests
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class GuestHandler extends AuthorizedHandler
{

    /**
     * @return $this
     */
    public function initForm()
    {
        $this->form = new GuestForm($this->formParams);

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this|Comment
     */
    protected function fillEntity(Comment $comment)
    {
        $comment = parent::fillEntity($comment);

        $form = $this->form;
        $comment
            ->setEmail($form->getElement('email')->getValue())
            ->setUserName($form->getElement('userName')->getValue());

        return $this;
    }
}
