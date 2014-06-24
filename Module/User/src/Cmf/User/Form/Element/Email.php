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
use Cmf\Form\Element\Text;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Email extends Text
{
    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $lng = \Cmf\Language\Factory::get($this);
        $defaultParams = [
            'attributes' => [
                'name' => 'email',
            ],
            'required' => true,
            'title' => $lng['title'],
            'filters' => [
                new Trim(),
            ],
        ];

        parent::__construct(array_merge($defaultParams, $params));

        $this->validators->setItems([new \Cmf\Data\Validator\Email()]);
        $this->filters->setItem(new Trim());
    }
}
