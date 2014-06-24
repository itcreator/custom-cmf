<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\Repository;

/**
 * repository for article comments
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>\
 */
class CommentRepository extends \Cmf\Comment\Model\Repository\CommentRepository
{
    protected $moduleName = 'Cmf\Article';
}
