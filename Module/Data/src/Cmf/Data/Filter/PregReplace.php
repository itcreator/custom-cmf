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
 * Regexp filter
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class PregReplace implements FilterInterface
{
    /**
     * @var string
     */
    protected $expression;
    /**
     * @var string
     */
    protected $replacement;

    /**
     * @param  $expression
     * @param string $replacement
     */
    public function __construct($expression, $replacement = '')
    {
        $this->expression = $expression;
        $this->replacement = $replacement;
    }

    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        return preg_replace($this->expression, $this->replacement, $value);
    }
}
