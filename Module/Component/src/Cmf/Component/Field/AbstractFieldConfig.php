<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Field;

/**
 * Abstract configuration for fields
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractFieldConfig
{
    /** @var array|false  */
    protected $config = false;

    /**
     * @return $this
     */
    abstract protected function init();

    /**
     * @param bool $disableHidden
     * @return array
     */
    public function getConfig($disableHidden = false)
    {
        if (false === $this->config) {
            $this->init();
        }

        if ($disableHidden) {
            $config = [];
            foreach ($this->config as $name => $field) {
                if (!isset($field['hideInList']) || !$field['hideInList']) {
                    $config[$name] = $field;
                }
            }
        } else {
            $config = $this->config;
        }

        return $config;
    }

    /**
     * @param string $fieldName
     * @return array|null
     */
    public function getConfigItem($fieldName)
    {
        if (false === $this->config) {
            $this->init();
        }

        return isset($this->config[$fieldName]) ? $this->config[$fieldName] : null;
    }
}
