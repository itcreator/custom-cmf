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
class Meta extends AbstractHelper
{
    /** @var array */
    protected $tags = [];

    /**
     * @param string $name
     * @param string $content
     * @return Meta
     */
    public function addTag($name, $content)
    {
        $this->tags[$name] = $content;

        return $this;
    }

    /**
     * @param string $name
     * @return Meta
     */
    public function deleteTag($name)
    {
        unset($this->tags[$name]);

        return $this;
    }

    /**
     * @param string $name
     * @return
     */
    public function getTag($name)
    {
        return $this->tags[$name];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function buildCode()
    {
        $res = '';
        foreach ($this->tags as $name => &$content) {
            $res .= '<meta http-equiv="' . $name . '" content="' . $content . '" >';
        }

        return $res;
    }

    /**
     * @return Meta
     */
    public function clear()
    {
        $this->tags = [];

        return $this;
    }
}
