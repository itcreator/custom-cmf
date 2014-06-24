<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
namespace Cmf\Category\Model\Entity;

use Cmf\Db\BaseEntity;
use Cmf\GedmoExtension\Tree\Traits\MaterializedPathEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Abstract entity for category
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\MappedSuperclass
 * @ORM\Entity(repositoryClass="Cmf\Category\Model\Repository\CategoryRepository")
 * @Gedmo\Tree(type="materializedPath")
 */
class Category extends BaseEntity
{
    use MaterializedPathEntity;
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Gedmo\TreePathSource
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=64)
     */
    protected $title;

    /**
     * @var string $summary
     *
     * @ORM\Column(name="summary", type="text", nullable=true)
     */
    protected $summary;

    /**
     * @var string $text
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var $this | null
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @param $title
     * @return Category
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
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $summary
     * @return Category
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }
}
