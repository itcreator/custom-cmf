<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Field;

use Cmf\Db\BaseEntity;

/**
 * Abstract Field
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractField
{
    /** @var  mixed */
    protected $value;

    /** @var \Cmf\Component\Field\Decorator\String */
    protected $decorator;

    /** @var \Zend\Config\Config */
    protected $config;

    /** @var null|\Cmf\Component\Grid\Table\Row\A */
    protected $row;

    /** @var BaseEntity */
    protected $entity;

    /**
     * @param \Zend\Config\Config | array $config
     * @param mixed $value
     */
    public function __construct($config, $value = null)
    {
        $this->config = is_array($config) ? new \Zend\Config\Config($config) : $config;
        if ($value instanceof \Doctrine\ORM\Proxy\Proxy) {
            $value = '';
        }
        $this->setValue($value);

        $className = $this->config->decorator ? $this->config->decorator : 'String';
        $className = '\Cmf\Component\Field\Decorator\\' . $className;
        $this->decorator = new $className($this);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return \Zend\Config\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Decorator\String
     */
    public function getDecorator()
    {
        return $this->decorator;
    }

    //TODO: отрефакторить методы setEntity, getEntity, setRow, getRow
    //вместо 2-х свойств должен быть один провайдер данных, использхуемый везде
    /**
     * @param BaseEntity $entity
     * @return $this
     */
    public function setEntity(BaseEntity $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return BaseEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param \Cmf\Component\Grid\Table\Row\A $row
     * @return $this
     */
    public function setRow(\Cmf\Component\Grid\Table\Row\A $row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * @return \Cmf\Component\Grid\Table\Row\A|null
     */
    public function getRow()
    {
        return $this->row;
    }
}
