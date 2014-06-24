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
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Select extends Input
{
    /** @var string */
    protected $tagName = 'select';

    /** @var \Cmf\Data\Source\AbstractSource */
    protected $dataSource;

    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $params = array_merge($this->defaultParams, ['dataSource' => null], $params);

        parent::__construct($params);

        $this->attributes->type = 'select';

        if ($params['dataSource']) {
            $this->dataSource = $params['dataSource'];
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = $this->dataSource->getData();

        if (!$this->required) {
            $emptyData = [[
                'value' => null,
                'title' => '',
                'level' => 0,
            ]];
            $data = array_merge($emptyData, $data);
        }

        return $data;
    }
}
