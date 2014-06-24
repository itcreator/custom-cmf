<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Link;

use Cmf\Component\Html\Attribute\AttributeCollection;
use Cmf\Structure\Collection\AssociateCollection;
use Cmf\System\Application;

abstract class AbstractLink
{
    /** @var string */
    protected $url;

    /** @var string */
    protected $title;

    /** @var AttributeCollection */
    protected $attributes = null;

    /** @var array */
    protected $params = [];

    /**
     * @param string $url
     * @param string $title
     * @param array $params
     */
    public function __construct($url, $title = '', array $params = [])
    {
        $this->attributes = new AttributeCollection();
        $this->params = $params;

        $this->setUrl($url)->setTitle($title ? $title : $url);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

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

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return AssociateCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
