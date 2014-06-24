<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Helper;

/**
 * Class for title tag
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Title extends AbstractHelper
{
    /** string */
    protected $title = '';

    /**
     * @param string $title
     * @return Title
     */
    public function addBefore($title)
    {
        $this->title = trim($title) . ' ' . $this->title;

        return $this;
    }

    /**
     * @param string $title
     * @return Title
     */
    public function addAfter($title)
    {
        $this->title .= ' ' . trim($title);
    }

    /**
     * @param string $title
     * @return Title
     */
    public function setTitle($title)
    {
        $this->title = trim($title);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return Title
     */
    public function clear()
    {
        $this->title = '';

        return $this;
    }
}
