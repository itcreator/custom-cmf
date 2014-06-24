<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Element;

use Cmf\Data\Validator\ValidatorInterface;
use Cmf\Form\Form;
use Cmf\Structure\Collection\MessageCollection;
use Cmf\Structure\Collection\Ordered\OrderedItemInterface;
use Cmf\Structure\Collection\Ordered\OrderedItemTrait;
use Cmf\System\Message;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractElement extends \Cmf\Component\Html\Element\AbstractElement implements OrderedItemInterface
{
    use OrderedItemTrait;

    const MSG_ERROR_REQUIRED = 'err_required';

    /** @var \Cmf\Form\Form */
    protected $parentForm = null;

    /** @var string */
    protected $title = '';

    /** @var mixed */
    protected $valueUnfiltered = null;

    /** @var bool */
    protected $required = false;

    /** @var \Cmf\Data\Validator\Collection */
    protected $validators = null;

    /** @var \Cmf\Data\Filter\Collection */
    protected $filters = null;

    /** @var boolean|null */
    protected $isValid = null;

    /** @var boolean */
    protected $loadingIsEnabled = true;

    /** @var MessageCollection $messages */
    protected $messages;

    /** @var mixed */
    protected $value;

    /** @var array */
    protected $defaultParams = [
        'validators' => [],
        'filters' => [],
        'attributes' => [],
        'title' => '',
        'value' => null,
        'required' => false,
        'weight' => null,
    ];

    /**
     * @throws \Cmf\Form\Exception
     * @param array $params
     */
    public function __construct($params = [])
    {
        $params = array_merge($this->defaultParams, $params);

        $this->messages = new MessageCollection();
        if (!is_array($params)) {
            throw new \Cmf\Form\Exception('Argument must be an array');
        }

        parent::__construct();

        $this->attributes->setFormElement($this);

        $this->validators = new \Cmf\Data\Validator\Collection();
        if (is_array($params['validators'])) {
            $this->validators->setItems($params['validators']);
        }

        $this->filters = new \Cmf\Data\Filter\Collection();
        if (is_array($params['filters'])) {
            $this->filters->setItems($params['filters']);
        }

        if (is_array($params['attributes'])) {
            $this->attributes->setItems($params['attributes']);
        } else {
            throw new \Cmf\Form\Exception('Parameter attribute must be an array');
        }

        $this->title = $params['title'];
        $this->setValue($params['value']);
        $this->required = $params['required'] ? true : false;

        if ($params['weight']) {
            $this->weight = $params['weight'];
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->attributes->getItem('name');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->attributes->setItem($name, 'name');

        return $this;
    }

    /**
     * This method generate string fo element attribute "name" (It's need for sub forms)
     *
     * @return string
     */
    public function getFullName()
    {
        $parentFullName = $this->parentForm ? $this->parentForm->getFullName() : null;
        $fullName = $parentFullName ? $parentFullName . '[' . $this->getName() . ']' : $this->getName();

        return $fullName;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $this->filters->filter($value);
        $this->valueUnfiltered = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed|string
     */
    public function getValueUnfiltered()
    {
        return $this->valueUnfiltered;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if (null !== $this->isValid) {
            return $this->isValid;
        } elseif ($this->required && (!strlen($this->valueUnfiltered) || !strlen($this->getValue()))) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages->setItem(new Message($lng[self::MSG_ERROR_REQUIRED], Message::TYPE_ERROR));
            $this->isValid = false;
        } elseif (!$this->required && !$this->valueUnfiltered) {
            $this->isValid = true;
        } else {
            $this->isValid = $this->validators->validate($this->getValue());
        }

        return $this->isValid;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages->setItems($this->validators->getMessages());
    }

    /**
     * @return Form
     */
    public function getParentForm()
    {
        return $this->parentForm;
    }

    /**
     * @param Form $parentForm
     * @return $this
     */
    public function setParentForm(Form $parentForm)
    {
        $this->parentForm = $parentForm;

        return $this;
    }

    /**
     * @return bool
     */
    public function getLoadingIsEnabled()
    {
        return $this->loadingIsEnabled;
    }

    /**
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isBroken()
    {
        return false === $this->isValid;
    }

    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators->setItem($validator);

        return $this;
    }
}
