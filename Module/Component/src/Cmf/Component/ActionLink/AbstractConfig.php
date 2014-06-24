<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\ActionLink;

/**
 * Abstract config for action links
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractConfig
{
    /** @var array|false  */
    protected $config = false;

    /**
     * @return $this
     */
    abstract protected function init();

    /**
     * @return array
     */
    public function getConfig()
    {
        if (false === $this->config) {
            $this->init();
        }

        return $this->config;
    }
}
