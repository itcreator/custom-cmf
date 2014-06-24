<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Pager\Adapter;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractPagerAdapter
{
    /** @var array|\Traversable|\Doctrine\ORM\QueryBuilder|mixed */
    protected $rawData = null;

    /**
     * This method return an collection of items for a page.
     *
     * @param int $offset
     * @param int $limit
     * @param array $sort
     * @return array
     */
    abstract public function getItems($offset, $limit, $sort = null);

    /**
     * @abstract
     * @return int
     */
    abstract public function getTotalCount();
}
