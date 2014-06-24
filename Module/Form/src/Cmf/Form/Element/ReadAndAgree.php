<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Element;

/**
 * Checkbox
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ReadAndAgree extends Checkbox
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $lng = \Cmf\Language\Factory::get($this);
        $defaultParams = [
            'attributes' => [
                'name' => 'confirmation',
            ],
            'required' => 'true',
            'title' => $lng['title'],
        ];

        parent::__construct(array_merge($defaultParams, $params));
    }
}
