<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category\Db\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * This filter hide root in categories list. Root is system record.
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CategoryRootFilter extends SQLFilter
{
    /**
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     * @return string
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->reflClass->isSubclassOf('Cmf\Category\Model\Entity\Category')) {
            return "";
        }

        return $targetTableAlias.'.parent_id IS NOT NULL';
    }
}
