<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
namespace Cmf\Component\Grid\Table\Row;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Component\Grid\Table\Rows\A as AbstractRow;
use Cmf\Structure\Collection\AssociateCollection;
use Cmf\Structure\Collection\LazyAssociateCollection;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class A
{
    /** @var \Cmf\Component\Grid\Table\Rows\A */
    protected $collection = null;
    /** @var LazyAssociateCollection */
    protected $fields = null;
    /** @var mixed */
    protected $dataProvider;
    /** @var null|AbstractConfig */
    protected $actionLinksConfig;

    /**
     * @param mixed $data
     * @param AbstractRow $collection
     * @param int|null $idField
     * @param AbstractConfig $actionLinksConfig
     */
    public function __construct($data, AbstractRow $collection, $idField = null, $actionLinksConfig = null)
    {
        $this->idField = $idField;
        $this->collection = $collection;
        $this->fields = new AssociateCollection();
        $this->dataProvider = $data;

        $this->actionLinksConfig = $actionLinksConfig;

        foreach ($collection->getFields()->getConfig() as $id => $fieldConfig) {
            $field = \Cmf\Component\Field\Factory::create($fieldConfig, $this->getRawField($id));
            $field->setRow($this);
            $this->fields->setItem($field, $id);
        }

        $row = $this;
        $initFields = function (LazyAssociateCollection $fields) use ($collection, $row) {
            $fieldsConfig = $collection->getFields();
            $config = $fieldsConfig instanceof AbstractFieldConfig ? $fieldsConfig->getConfig(true) : [];

            foreach ($config as $id => $fieldConfig) {
                $field = \Cmf\Component\Field\Factory::create($fieldConfig, $row->getRawField($id));
                $field->setRow($row);
                $fields->setItem($field, $id);
            }
        };

        $this->fields = new LazyAssociateCollection($initFields);

    }

    /**
     * @abstract
     * @param string $key
     * @return mixed
     */
    abstract public function getRawField($key);

    /**
     * @param \Cmf\Component\Grid\Table\Rows\A $collection
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return \Cmf\Component\Grid\Table\Rows\A|null
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @return AssociateCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return mixed|null
     */
    public function getId()
    {
        /** @var $field \Cmf\Component\Field\AbstractField|null */
        $field = $this->fields->getItem($this->idField);

        return $field ? $field->getValue() : null;
    }

    /**
     * @return \Cmf\Component\ActionLink\Collection
     */
    public function getActionLinks()
    {
        $primaryField = isset($this->fields[$this->idField]) ? $this->fields[$this->idField] : null;

        return \Cmf\Component\ActionLink\Factory::createLinks($this->actionLinksConfig, $primaryField);
    }
}
