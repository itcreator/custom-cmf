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
use Cmf\System\Application;

/**
 * Forwarding implementation for blocks
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Forward extends AbstractResponse
{
    /** @var string */
    protected $blockName;

    /**
     * @param string $blockName
     * @return $this
     */
    public function setForwardData($blockName)
    {
        $this->blockName = $blockName;

        return $this;
    }

    /**
     * @return AbstractBlock
     */
    public function handle()
    {
        return Application::getBlockManager()->createBlockInstance($this->blockName);
    }
}
