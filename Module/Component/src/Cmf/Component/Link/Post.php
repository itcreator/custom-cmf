<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Link;

use Cmf\Form\Element\Submit;
use Cmf\Form\Form;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Post extends AbstractLink
{
    /** @var null | Form */
    protected $form = null;

    /**
     * @return Form|null
     */
    public function getForm()
    {
        if ($this->form) {
            return $this->form;
        }

        $form = new Form([
            'action' => $this->getUrl(),
        ]);

        $form->setElement(new Submit(['value' => $this->getTitle()]));
        $form->getAttributes()->setItems($this->getAttributes());

        return $form;
    }
}
