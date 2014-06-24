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
 * Adapter for arrays and traversable objects
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Traversable extends AbstractPagerAdapter
{
    /**
     * @param array | \Traversable $data
     */
    public function __construct(&$data)
    {
        $this->rawData = & $data;
    }

    /**
     * This method return an collection of items for a page.
     *
     * @param  integer $offset
     * @param  integer $limit
     * @param \Cmf\System\Sort $sort
     * @return array
     */
    public function getItems($offset, $limit, $sort = null)
    {
        $items = [];
        $cnt = count($this->rawData);
        for ($i = $offset; $i < $offset + $limit && $i < $cnt; $i++) {
            $items[] = $this->rawData[$i];
        }

        return $items;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return count($this->rawData);
    }
}
