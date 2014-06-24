<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Source;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractSource
{
    /**
     * @return array
     */
    abstract public function getData();

    /**
     * @param string $key
     * @return array|bool false
     */
    public function getDataItem($key)
    {
        $data = $this->getData();
        return isset($data[$key]) ? $data[$key] : false;
    }

    /**
     * @param string $key
     * @return string|bool
     */
    public function getDataItemTitle($key)
    {
        $item = $this->getDataItem($key);
        if (is_array($item) && isset($item['title'])) {
            return $item['title'];
        } else {
            return false;
        }
    }
}
