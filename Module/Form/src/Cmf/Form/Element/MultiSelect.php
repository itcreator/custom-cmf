<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Element;

use Cmf\Form\Constants;
use Cmf\Form\Form;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class MultiSelect extends Form
{
    /** @var \Cmf\Data\Source\AbstractSource */
    protected $dataSource;

    /** @var bool */
    protected $elementsIsInitialized = false;

    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        $defaultParams = [
            'type' => Constants::TYPE_SUB_FORM,
            'dataSource' => null,
        ];

        $params = array_merge($this->defaultParams, $defaultParams, $params);

        parent::__construct($params);

        if ($params['dataSource']) {
            $this->dataSource = $params['dataSource'];
        }
    }

    /**
     * @return $this
     */
    protected function initializeElements()
    {
        if ($this->elementsIsInitialized) {
            return $this;
        }

        $this->elementsIsInitialized = true;

        foreach ($this->dataSource->getData() as $item) {
            $element = new Checkbox();
            $element->setTitle($item['title'])->setName($item['value']);
            $this->setElement($element);
        }

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValue($values = [])
    {
        foreach ($this->getElements() as $element) {
            $element->setValue(false);
        }

        foreach ($values as $key => $value) {
            $this->getElement($key)->setValue(true);
        }

        return $this;
    }
}
