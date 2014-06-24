<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\GedmoExtension\Tree\Traits;

//TODO: create interface for this
/**
 * Trait for gedmo tree materialized path
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
trait MaterializedPathEntity
{
//    /**
//     * @var integer $id
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="IDENTITY")
//     * @Gedmo\TreePathSource
//     */
//    protected $id;

    /**
     * @Gedmo\TreePath
     * @ORM\Column(name="path", type="string", length=3000, nullable=true)
     */
    protected $path;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    protected $level;


    /**
     * @param MaterializedPathEntity $parent
     * @return $this
     */
    public function setParent($parent = null)
    {
        //TODO: add type for parameter
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return MaterializedPathEntity | null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return MaterializedPathEntity[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
