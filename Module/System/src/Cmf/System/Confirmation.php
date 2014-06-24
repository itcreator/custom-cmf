<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\System;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Confirmation
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $message;

    /** @var array */
    public $urlNo = [];

    public function __construct($title, $message = '', array $urlNo = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->urlNo = $urlNo;
    }

    /**
     * @return string
     */
    public function getUrlYes()
    {
        $urlParams = Application::getRequest()->getVars();
        $urlYes = array_merge($urlParams, ['start' => 1]);

        return Application::getUrlBuilder()->build($urlYes);
    }

    /**
     * @return string
     */
    public function getUrlNo()
    {
        return Application::getUrlBuilder()->build($this->urlNo);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
