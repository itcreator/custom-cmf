<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Html\Element;

use Cmf\Component\Html\Attribute\AttributeCollection;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractElement
{
    /** @var AttributeCollection */
    protected $attributes = null;

    /** @var string */
    protected $tagName = '';

    public function __construct()
    {
        $this->attributes = new AttributeCollection();
    }

    /**
     * @return AttributeCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->tagName;
    }
}
