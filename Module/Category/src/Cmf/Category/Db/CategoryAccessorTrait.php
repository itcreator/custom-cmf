<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category\Db;

use Cmf\Category\Model\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Use it in articles and news entities for access to categories
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
trait CategoryAccessorTrait
{
    /**
     * For adding article to category use only $category->addArticle($article)
     *
     * @param Category $category
     * @return $this
     */
    public function addToCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @param Collection $categories
     * @return $this
     */
    public function setCategories(Collection $categories = null)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection|PersistentCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

}
