<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Table\Row;

use Cmf\Db\BaseEntity;

/**
 * Row adapter for Doctrine
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class DoctrineRowAdapter extends A
{
    /** @var BaseEntity */
    protected $dataProvider;

    /**
     * @param string $key
     * @return string
     */
    public function getRawField($key)
    {
        $getter = 'get' . $key;
        $rawField = $this->dataProvider->$getter();

        return $rawField;
    }
}
