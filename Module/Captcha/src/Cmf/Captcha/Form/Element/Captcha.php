<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Captcha\Form\Element;

use Cmf\Form\Element\Input;
use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Captcha extends Input
{
    /** @var int */
    protected $weight = 900;

    public function __construct($params = [])
    {
        $lng = \Cmf\Language\Factory::get($this);
        $defaultParams = [
            'attributes' => [
                'name' => 'captcha',
            ],
            'required' => true,
            'title' => $lng['title'],
        ];

        parent::__construct(array_merge($defaultParams, $params));

        $this->attributes->type = 'captcha';
        $this->validators->setItem(new \Cmf\Captcha\Validator\Captcha());
    }

    public function getImagePath()
    {
        return Application::getUrlBuilder()->build(['controller' => 'captcha', 'module' => 'Cmf\Captcha']);
    }
}
