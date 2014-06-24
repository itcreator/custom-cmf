<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Model\Repository;

use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;

/**
 * Repository for comment entity
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CommentRepository extends MaterializedPathRepository
{
    //TODO: make it in module manager
    /** @var string  */
    protected $moduleName = '';

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }
}
