<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Block\Response;

use Cmf\Block\AbstractBlock;

/**
 * Abstract block response
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractResponse
{
    /** @var array */
    protected $params = [];
    /** @var AbstractBlock */
    protected $block;

    /**
     * @param AbstractBlock $block
     * @param array $params
     */
    public function __construct(AbstractBlock $block, array $params = [])
    {
        $this->block = $block;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    abstract public function handle();
}
