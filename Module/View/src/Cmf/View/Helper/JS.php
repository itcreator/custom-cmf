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
class JS extends AbstractHelper
{
    /**  @var array */
    protected $scripts = [];

    /** @var array */
    protected $codes = [];

    /** @var string */
    protected $path = '';

    /**
     * @param string $href
     * @return JS
     */
    public function addScript($href)
    {
        $this->deleteScript($href);
        $this->scripts[] = trim($href);

        return $this;
    }

    /**
     * @param string $href
     * @return JS
     */
    public function deleteScript($href)
    {
        foreach ($this->scripts as $key => $val) {
            if (trim($href) == $val) {
                unset($this->scripts[$key]);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * @param string $code
     * @return JS
     */
    public function addCode($code)
    {
        $this->codes[] = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function buildCode()
    {
        $res = '';
        foreach ($this->scripts as $href) {
            $res .= '<script type = "text/javascript" src = "' . $this->path . '/' . $href . '"></script>' . "\r\n";
        }

        foreach ($this->codes as $code) {
            $res .= '<script type = "text/javascript">' . $code . '</script>' . "\r\n";
        }

        return $res;
    }

    /**
     * @return JS
     */
    public function clearCodes()
    {
        $this->codes = [];

        return $this;
    }

    /**
     * @return JS
     */
    public function clearScripts()
    {
        $this->scripts = [];

        return $this;
    }

    /**
     * @return JS
     */
    public function clear()
    {
        $this->clearCodes()->clearScripts();

        return $this;
    }
}
