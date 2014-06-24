<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Repository for articles comments
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="article_category")
 * @ORM\Entity(repositoryClass="Cmf\Category\Model\Repository\CategoryRepository")
 * @Gedmo\Tree(type="materializedPath")
 */
class Category extends \Cmf\Category\Model\Entity\Category
{
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="categories")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @param Article $article
     * @return Category
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
        $article->addToCategory($this);

        return $this;
    }
}
