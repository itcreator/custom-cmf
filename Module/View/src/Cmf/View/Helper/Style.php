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
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Style extends AbstractHelper
{
    /** @var array */
    protected $styles = [];

    /**
     * folder of current theme
     *
     * @var string
     */
    protected $path = '/';

    /**
     * this method add css file
     *
     * @param string $href
     * @param string $path
     * @return Style
     */
    public function addStyle($href, $path = null)
    {
        $key = $this->calculateKey($href, $path);
        $this->styles[$key] = 1;

        return $this;
    }

    /**
     * This method remove css file from list
     *
     * @param string $href
     * @param string $path
     * @return Style
     */
    public function deleteStyle($href, $path = null)
    {
        $key = $this->calculateKey($href, $path);
        if (isset($this->styles[$key])) {
            unset($this->styles[$key]);
        }

        return $this;
    }

    /**
     * @param string $href
     * @param string $path
     * @return string
     */
    protected function calculateKey($href, $path)
    {
        $path = $this->path . str_replace('\\', '/', $path) . '/';

        return trim($path) . trim($href);
    }

    /**
     * This method generate code for include css files
     *
     * @return string
     */
    public function buildCode()
    {
        $res = '';
        foreach ($this->styles as $href => $val) {
            $res .= '<link href="' . $href . '" rel="stylesheet" type="text/css" />' . "\r\n";
        }

        return $res;
    }

    //--getters and setters

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @return Style
     */
    public function clear()
    {
        $this->styles = [];

        return $this;
    }
}
