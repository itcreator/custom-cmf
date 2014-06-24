<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Form;

use Cmf\Data\Filter\Trim;
use Cmf\Form\Element\Submit;
use Cmf\Form\Element\TextArea;
use Cmf\Form\Form;

/**
 * Comment form for authorized user
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class AuthorizedForm extends Form
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);

        $lng = \Cmf\Language\Factory::get($this);
        $text = new TextArea([
            'required' => true,
            'attributes' => [
                'name' => 'text',
            ],
            'title' => $lng['comment'],
            'filters' => [
                new Trim()
            ],
        ]);
        $this->setElement($text);

        $this->setElement(new Submit([
            'value' => $lng['addCommentBtnTitle'],
        ]));
    }
}
