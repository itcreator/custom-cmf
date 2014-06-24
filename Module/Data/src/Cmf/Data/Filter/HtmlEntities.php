<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Filter;

/**
 * Html entities filter
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class HtmlEntities implements FilterInterface
{
    /** @var int */
    protected $quoteStyle;
    /**
     * @var string
     */
    protected $charSet;

    /**
     * @param string $charSet
     * @param int $quoteStyle
     */
    public function __construct($charSet = 'utf-8', $quoteStyle = ENT_COMPAT)
    {
        $this->quoteStyle = $quoteStyle;
        $this->charSet = $charSet;
    }

    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return htmlentities((string)$value, $this->quoteStyle, $this->charSet);
    }

    /**
     * @return int
     */
    public function getQuoteStyle()
    {
        return $this->quoteStyle;
    }

    /**
     * @param int $quoteStyle
     * @return void
     */
    public function setQuoteStyle($quoteStyle)
    {
        $this->quoteStyle = $quoteStyle;
    }

    /**
     * @return string
     */
    public function getCharSet()
    {
        return $this->charSet;
    }

    /**
     * @param string $charSet
     * @return HtmlEntities
     */
    public function setCharSet($charSet)
    {
        $this->charSet = $charSet;

        return $this;
    }
}
