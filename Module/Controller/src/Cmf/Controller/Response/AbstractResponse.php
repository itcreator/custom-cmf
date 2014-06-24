<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Controller\Response;

use Cmf\Controller\AbstractController;

/**
 * Abstract response for MvcRequest
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractResponse
{
    /** @var AbstractController */
    protected $controller;

    /** @var array */
    protected $renderData = [];

    /**
     * @param AbstractController $controller
     * @param array $renderData
     */
    public function __construct(AbstractController $controller, array $renderData = [])
    {
        $this->controller = $controller;
        $this->renderData = $renderData;
    }

    /**
     * @return mixed
     */
    abstract public function handle();
}
