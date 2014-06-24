<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Table\Rows;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\Field\AbstractFieldConfig as FieldConfig;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class A implements \Iterator
{
    /** @var array */
    protected $rows = [];

    /** @var int|null */
    protected $idField = null;

    /**
     * Counter for Iterator
     *
     * @var int
     */
    protected $rowsCounter = 0;

    /** @var \Cmf\Component\Grid\Table */
    protected $table = null;

    /** @var FieldConfig  fields description */
    protected $fields = [];

    /** @var AbstractConfig */
    protected $actionLinksConfig;

    /**
     * @param mixed $data
     * @param int | null $idField
     * @param FieldConfig|null $fields
     * @param AbstractConfig $links
     */
    public function __construct($data, $idField = null, FieldConfig $fields = null, AbstractConfig $links = null)
    {
        $this->idField = $idField;
        $this->fields = $fields;
        $this->actionLinksConfig = $links;
    }

    /**
     * @return int|null
     */
    public function getIdField()
    {
        return $this->idField;
    }

    /**
     * go to first row
     *
     * @return void
     */
    public function rewind()
    {
        $this->rowsCounter = 0;
    }

    /**
     * This method return current row
     *
     * @return \Cmf\Component\Grid\Table\Row\A
     */
    public function current()
    {
        return isset($this->rows[$this->rowsCounter]) ? $this->rows[$this->rowsCounter] : false;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->rowsCounter;
    }

    /**
     * @return \Cmf\Component\Grid\Table\Row\A
     */
    public function next()
    {
        ++ $this->rowsCounter;

        return $this->current();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * @param \Cmf\Component\Grid\Table $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return \Cmf\Component\Grid\Table|null
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return FieldConfig|null
     */
    public function getFields()
    {
        return $this->fields;
    }
}
