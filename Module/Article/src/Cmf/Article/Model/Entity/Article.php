<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\Entity;

use Cmf\Category\Db\CategoryAccessorTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="article_article")
 * @ORM\Entity
 */
class Article extends BaseArticle
{
    use CategoryAccessorTrait;

    /**
     * @var Category[]
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinTable(name="article_2_category")
     */
    protected $categories;

    /**
     * @var Collection|Comment[]
     * @ORM\OneToMany(targetEntity="Cmf\Article\Model\Entity\Comment", mappedBy="content")
     **/
    protected $comments;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }
}
