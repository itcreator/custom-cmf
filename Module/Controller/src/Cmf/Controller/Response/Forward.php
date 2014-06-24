<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Controller\Response;

use Cmf\Controller\MvcRequest;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Forward extends AbstractResponse
{
    /** @var string */
    protected $forwardModule;

    /** @var string */
    protected $forwardController;

    /** @var string */
    protected $forwardAction;

    /**
     * @param string $action
     * @param string $controller
     * @param string $module
     * @return Forward
     */
    public function setForwardData($action, $controller = null, $module = null)
    {
        $this->forwardAction = $action;
        $this->forwardController = $controller;
        $this->forwardModule = $module;

        return $this;
    }

    /**
     * @return MvcRequest
     */
    public function handle()
    {
        return new MvcRequest($this->forwardModule, $this->forwardController, $this->forwardAction);
    }
}
