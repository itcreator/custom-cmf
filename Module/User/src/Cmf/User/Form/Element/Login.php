<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */


namespace Cmf\User\Form\Element;

use Cmf\Data\Filter\Trim;
use Cmf\Data\Validator\AlNum;
use Cmf\Data\Validator\StrLen;
use Cmf\Form\Element\Text;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Login extends Text
{
    public function __construct($params = [])
    {
        $lng = \Cmf\Language\Factory::get($this);

        $defaultParams = [
            'attributes' => ['name' => 'login',],
            'required' => true,
            'title' => $lng['title'],
        ];

        parent::__construct(array_merge($defaultParams, $params));

        $this->validators->setItems([
            new StrLen(3, 50),
            new AlNum(true, true, false, '_'),
        ]);

        $this->filters->setItem(new Trim());
    }
}
