<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Form;

use Cmf\Captcha\Form\Element\Captcha;
use Cmf\Data\Filter\Trim;
use Cmf\Form\Element\Input;
use Cmf\User\Form\Element\Email;

/**
 * Comment form for guests
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class GuestForm extends AuthorizedForm
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);

        $lng = \Cmf\Language\Factory::get($this);

        $name = new Input([
            'attributes' => ['name' => 'userName'],
            'title' => $lng['name'],
            'weight' => 1,
            'required' => true,
            'filters' => [
                new Trim(),
            ],
        ]);

        $this
            ->setElement($name)
            ->setElement(new Email(['weight' => 5]))
            ->setElement(new Captcha())
        ;
    }
}
