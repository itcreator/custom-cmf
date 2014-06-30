<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form;

use Cmf\Component\Html\Attribute\AttributeCollection;
use Cmf\Data\Validator\HttpMethod;
use Cmf\Form\Element\AbstractElement;
use Cmf\Structure\Collection\AssociateCollection;
use Cmf\Structure\Collection\MessageCollection;
use Cmf\Structure\Collection\Ordered\OrderedCollection;
use Cmf\Structure\Collection\SimpleCollection;
use Cmf\System\Application;
use Cmf\System\Message;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Form extends AbstractElement
{
    /**
     * Name for hidden form element
     */
    const START_INPUT_NAME = 'start';
    const ERR_INCORRECT_FORM = 'err_incorrect_form';

    /** @var OrderedCollection Collection of form elements */
    protected $elements = null;

    /** @var int */
    protected $type = null;

    /** @var MessageCollection */
    protected $messages = null;

    /** @var array */
    protected $defaultParams = [
        'action' => '',
        'method' => Constants::HTTP_METHOD_POST,
        'title' => '',
        'type' => Constants::TYPE_FORM,
    ];

    /**
     * @throws Exception
     * @param array $params
     */
    public function __construct($params = [])
    {
        //TODO: create element SubForm
        if (!is_array($params)) {
            throw new Exception('Argument must be an array');
        }

        $params = array_merge($this->defaultParams, $params);

        $this->attributes = new AttributeCollection();
        $this->attributes->setFormElement($this);

        $this->elements = new OrderedCollection();

        if (isset($params['attributes'])) {
            if (is_array($params['attributes'])) {
                $this->attributes->setItems($params['attributes']);
            } else {
                throw new Exception('Parameter attribute must be an array');
            }
        }

        $this->type = isset($params['type']) ? $params['type'] : Constants::TYPE_FORM;
        $this->attributes->method = $params['method'];
        $this->attributes->action = $params['action'];

        $this->validators = new \Cmf\Data\Validator\Collection();
        if (isset($params['validators']) && is_array($params['validators'])) {
            $this->validators->setItems($params['validators']);
        }

        $this->filters = new \Cmf\Data\Filter\Collection();
        if (isset($params['filters']) && is_array($params['filters'])) {
            $this->filters->setItems($params['filters']);
        }

        $this->title = $params['title'];

        if (Constants::TYPE_FORM == $this->type) {
            $this->setElement(new Element\Hidden([
                'attributes' =>
                    ['name' => self::START_INPUT_NAME],
                'value' => 1,
            ]));

            $this->validators->setItem(new HttpMethod($this));
        }
        $this->messages = new MessageCollection();
    }

    /**
     * @return AssociateCollection|AbstractElement[]
     */
    public function getElements()
    {
        $this->initializeElements();

        return $this->elements;
    }

    /**
     * @param string $name
     * @return AbstractElement
     */
    public function getElement($name)
    {
        return $this->getElements()->getItem($name);
    }

    /**
     * @param AbstractElement $element
     * @return Form
     */
    public function setElement(AbstractElement $element)
    {
        //TODO: add weight for ordering
        $this->elements->setItem($element, $element->attributes->name);
        $element->setParentForm($this);

        return $this;
    }

    /**
     * @param string $name
     * @return Form
     */
    public function removeElement($name)
    {
        $this->getElements()->removeItem($name);

        return $this;
    }

    /**
     * @return string|null
     */
    public function render()
    {
        if (Constants::TYPE_FORM == $this->type) {
            $attr = $this->attributes->render();
            $view = Application::getViewProcessor();
            $children = $view->render($this);

            return "<form $attr />$children</form>";
        } else {

            return null;
        }
    }

    /**
     * @return Form
     */
    public function getValuesFromRequest()
    {
        $data = Application::getRequest()->getVars($this->attributes->method);

        foreach ($_FILES as $key => $val) {
            $data[$key] = $val;
        }

        $this->valueUnfiltered = $data;
        $this->value = $data;
        foreach ($this->elements as $key => $element) {
            if ($element instanceof Form) {
                /** @var $element Form */
                $element->setValue(isset($data[$key]) ? $data[$key] : null);
            } else {
                /** @var $element AbstractElement */
                if ($element->getLoadingIsEnabled()) {
                    $element->setValue(isset($data[$key]) ? $data[$key] : null);
                }
            }
        }

        return $this;
    }

    /**
     * @param array $values
     * @return Form
     */
    public function setValue($values = [])
    {
        $this->initializeElements();

        $this->valueUnfiltered = $values;
        foreach ($this->elements as $key => $element) {
            if ($element instanceof Form) {
                /** @var $element Form */
                $element->setValue(isset($values[$key]) ? $values[$key] : []);
            } else {
                /** @var $element AbstractElement */
                $element->setValue(isset($values[$key]) ? $values[$key] : null);
            }
        }

        return $this;
    }

    protected function initializeElements()
    {
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return (bool)Application::getRequest()->get(self::START_INPUT_NAME, $this->attributes->method);
    }

    public function validate()
    {
        /**
         * @var $element AbstractElement
         */
        $isValid = true;
        if (!$this->validators->validate($this->getValue())) {
            $isValid = false;
            $this->messages->setItems($this->validators->getMessages());
        }
        foreach ($this->elements as $element) {
            if (!$element->validate()) {
                $isValid = false;
            }
        }
        if (!$isValid) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages->setItem(new Message($lng[self::ERR_INCORRECT_FORM], Message::TYPE_ERROR));
        }

        return $isValid;
    }

    /**
     * @return SimpleCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Form $parentForm
     * @return $this|AbstractElement
     */
    public function setParentForm(Form $parentForm)
    {
        $this->type = Constants::TYPE_SUB_FORM;

        parent::setParentForm($parentForm);

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getMethod()
    {
        return $this->getAttributes()->getItem('method');
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->getAttributes()->setItem($method, 'method');

        return $this;
    }
}
