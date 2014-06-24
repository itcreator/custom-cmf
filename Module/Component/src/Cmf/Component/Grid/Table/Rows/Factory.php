<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Table\Rows;

use Cmf\Component\ActionLink\AbstractConfig;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    /**
     * @param mixed $data
     * @param string|null $idField
     * @param array $fields
     * @param AbstractConfig $actionLinksConfig
     * @return ArrayAdapter|DoctrineEntityRepositoryAdapter
     * @throws \Cmf\Component\Grid\Exception
     */
    public static function create($data, $idField = null, $fields = [], $actionLinksConfig = null)
    {
        if ($data instanceof \Doctrine\ORM\EntityRepository) {
            $result = new DoctrineEntityRepositoryAdapter($data, $idField, $fields, $actionLinksConfig);
        } elseif (is_array($data) || $data instanceof \Cmf\Component\Grid\Pager) {
            $result = new ArrayAdapter($data, $idField, $fields, $actionLinksConfig);
        } else {
            throw new \Cmf\Component\Grid\Exception('Unknown data type');
        }

        return $result;
    }
}
