<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Table\Row;

use Cmf\Component\ActionLink\AbstractConfig;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    /**
     * @param mixed $data
     * @param \Cmf\Component\Grid\Table\Rows\A $collection
     * @param string|null $idField
     * @param AbstractConfig $actionLinksConfig
     * @return ArrayAdapter|A
     * @throws \Cmf\Component\Grid\Exception
     */
    public static function create($data, $collection, $idField = null, $actionLinksConfig = null)
    {
        if (is_array($data) || $data instanceof \Cmf\Component\Grid\Pager) {
            $adapter = new ArrayAdapter($data, $collection, $idField, $actionLinksConfig);
        } elseif (strpos(get_class($data), '\Model\Entity\\') || 0 === strpos(get_class($data), 'Proxy\\')) {
            $adapter = new DoctrineRowAdapter($data, $collection, $idField, $actionLinksConfig);
        } else {
            throw new \Cmf\Component\Grid\Exception('Incorrect data type');
        }
        return $adapter;
    }
}
