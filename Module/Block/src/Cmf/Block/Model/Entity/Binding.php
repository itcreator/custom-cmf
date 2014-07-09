<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Block\Model\Entity;

use Cmf\Db\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="block_binding")
 * @ORM\Entity(repositoryClass="Cmf\Block\Model\Repository\BindingRepository")
 */
class Binding extends BaseEntity
{
    /**
     * @var \Cmf\Block\Model\Entity\Block
     *
     * @ORM\ManyToOne(targetEntity="Cmf\Block\Model\Entity\Block")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $block;

    /**
     * @var \Cmf\Block\Model\Entity\Container
     *
     * @ORM\ManyToOne(targetEntity="Cmf\Block\Model\Entity\Container")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $container;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $moduleName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $controllerName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $params;

    /**
     * @param Block $block
     * @return $this
     */
    public function setBlock(Block $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param Container $container
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @param string $name
     * @return Binding
     */
    public function setControllerName($name)
    {
        $this->controllerName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param string $name
     * @return Binding
     */
    public function setModuleName($name)
    {
        $this->moduleName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return json_decode($this->params, true);
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = json_encode($params);

        return $this;
    }
}
