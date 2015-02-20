<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Table;

use Cmf\Structure\Collection\AssociateCollection;
use Cmf\System\Application;
use Cmf\System\Sort;

/**
 * Table header
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Header
{
    /** @var AssociateCollection */
    protected $fields;

    /**
     * @param AssociateCollection $fields
     */
    public function __construct(AssociateCollection $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return AssociateCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getHeaderFields()
    {
        $request = Application::getRequest();
        $params = $request->getVars();
        $sort = $request->getSort();
        if (isset($params['sort'])) {
            unset($params['sort']);
        }

        $fields = [];

        foreach ($this->fields as $name => $field) {
            $currParam = $params;

            $fieldSort = new Sort($sort->getRequestVariable());
            $fieldSort->setField($name);
            if ($sort->isSortField($name) && !$sort->isDirection(Sort::DIRECTION_DESC)) {
                $currParam['sort'] = $fieldSort->setDirection(Sort::DIRECTION_DESC);
            } elseif (!$sort->isSortField($name)) {
                $currParam['sort'] = $fieldSort;
            }

            $fields[] = array_merge($field, ['url' => Application::getUrlBuilder()->build($currParam)]);
        }

        return $fields;
    }
}
