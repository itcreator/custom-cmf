<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Link;

use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ActionLink extends AbstractLink
{
    /**
     * @param string | array $url
     * @return $this
     */
    public function setUrl($url)
    {
        if (is_array($url)) {
            $this->url = Application::getUrlBuilder()->build($url);
        } else {
            $this->url = (string)$url;
        }
        $this->attributes->href = $this->url;

        return $this;
    }
}
