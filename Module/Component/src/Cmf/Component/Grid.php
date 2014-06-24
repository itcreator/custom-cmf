<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Grid
{
    /** @var Grid\Table */
    protected $table = null;

    /** @var Grid\Filter */
    protected $filter = null;

    /** Grid\Pager */
    protected $pager = null;

    /**
     * @param array $params
     */
    public function __construct($params)
    {
        //TODO: implement filter
        $filterParams = isset($params['filter']) ? $params['filter'] : [];
        $this->filter = new Grid\Filter($filterParams);

        $pagerParams = isset($params['pager']) ? $params['pager'] : [];
        $this->pager = Grid\Pager::factory(isset($params['data']) ? $params['data'] : [], $pagerParams);

        $params['data'] = $this->pager;
        $this->table = new Grid\Table($params);
    }

    /**
     * @return Grid\Filter|null
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return Grid\Table|null
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return Grid\Pager|null
     */
    public function getPager()
    {
        return $this->pager;
    }
}
