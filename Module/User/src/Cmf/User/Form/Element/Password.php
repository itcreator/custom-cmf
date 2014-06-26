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

/**
 * Password with default text
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Password extends \Cmf\Form\Element\Password
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $lng = \Cmf\Language\Factory::get($this);
        $defaultParams = [
            'attributes' => ['name' => 'password'],
            'required' => 'true',
            'title' => $lng['title'],
        ];

        parent::__construct(array_merge($defaultParams, $params));

        $this->filters->setItem(new Trim());
    }
}
