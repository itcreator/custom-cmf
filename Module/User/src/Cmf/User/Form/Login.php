<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Form;

use Cmf\Form\Element\Checkbox;
use Cmf\Form\Element\Submit;
use Cmf\Form\Form;
use Cmf\User\Form\Element\Password;
use Cmf\User\Form\Element\Login as LoginElement;

/**
 * Login form
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Login extends Form
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);

        $lng = \Cmf\Language\Factory::get($this);
        $this
            ->setElement(new LoginElement())
            ->setElement(new Password())
            ->setElement(new Checkbox([
                'attributes' => [
                    'name' => 'rememberUser',
                ],
                'value' => true,
                'title' => $lng['rememberUserTitle'],
            ]))
            ->setElement(new Submit([
                'value' => $lng['loginBtnTitle'],
            ]));
    }
}
