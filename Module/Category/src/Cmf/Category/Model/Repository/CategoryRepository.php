<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category\Model\Repository;

use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;

/**
 * Repository for comment entity
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CategoryRepository extends MaterializedPathRepository
{
    /**
     * {@inheritDoc}
     */
    public function getRootNodes($sortByField = null, $direction = 'asc')
    {
        $em = $this->getEntityManager();

        $em->getFilters()->disable('category-root-filter');
        $result = parent::getRootNodes($sortByField, $direction);
        $em->getFilters()->enable('category-root-filter');

        return $result;
    }
}
