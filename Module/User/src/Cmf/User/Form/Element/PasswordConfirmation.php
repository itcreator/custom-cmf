<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Form\Element;

use Cmf\Form\Element\AbstractElement;
use Cmf\Data\Validator\Confirmation;
use Cmf\Form\Element\Password;

/**
 * Password confirmation
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class PasswordConfirmation extends Password
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $lng = \Cmf\Language\Factory::get($this);
        $defaultParams = [
            'attributes' => ['name' => 'passwordConfirmation'],
            'required' => 'true',
            'title' => $lng['title'],
        ];

        parent::__construct(array_merge($defaultParams, $params));

        if (isset($params['element']) && $params['element'] instanceof AbstractElement) {
            $this->validators->setItem(new Confirmation($params['element']));
        }
    }
}
