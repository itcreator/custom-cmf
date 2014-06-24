<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Element;

use Cmf\System\Application;

/**
 * Data in dataSource must be ordered by tree path or by tree left key
 * You can add key level to dataSource for format html select options
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class EntityTreeSelect extends Entity
{
    /**
     * @return array
     */
    public function getData()
    {
        $filters = Application::getEntityManager()->getFilters();
        $filters->disable('category-root-filter');
        $data = parent::getData();
        $filters->enable('category-root-filter');

        return $data;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $filters = Application::getEntityManager()->getFilters();
        $filters->disable('category-root-filter');
        $isValid = parent::validate();
        $filters->enable('category-root-filter');

        return $isValid;
    }
}
