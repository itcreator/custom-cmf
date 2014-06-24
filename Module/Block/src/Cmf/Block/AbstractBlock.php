<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Block;

/**
 * Abstract block
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractBlock
{
    /** @var array  */
    protected $params = [];

    public function __construct(array $params = [])
    {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * @return \Cmf\Block\Response\AbstractResponse
     */
    abstract public function handle();
}
