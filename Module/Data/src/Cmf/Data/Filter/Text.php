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
 * Filter for form element text
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class FilterText implements FilterInterface
{
    /**
     * @var bool
     */
    protected $allowCyrillic;
    /**
     * @var bool
     */
    protected $allowEnglish;
    /**
     * @var bool
     */
    protected $allowBlankSpace;
    /**
     * @var bool
     */
    protected $allowBlankLine;
    /**
     * @var bool
     */
    protected $allowDigits;
    /**
     * @var bool
     */
    protected $allowDot;
    /**
     * @var bool
     */
    protected $allowMinus;

    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }

        if (isset ($params['allowCyrillic'])) {
            $this->allowCyrillic = $params['allowCyrillic'];
        } else {
            $this->allowCyrillic = true;
        }

        if (isset ($params['allowEnglish'])) {
            $this->allowEnglish = $params['allowEnglish'];
        } else {
            $this->allowEnglish = true;
        }

        if (isset ($params['allowBlankSpace'])) {
            $this->allowBlankSpace = $params['allowBlankSpace'];
        } else {
            $this->allowBlankSpace = false;
        }

        if (isset ($params['allowBlankLine'])) {
            $this->allowBlankLine = $params['allowBlankLine'];
        } else {
            $this->allowBlankLine = false;
        }

        if (isset ($params['allowDigits'])) {
            $this->allowDigits = $params['allowDigits'];
        } else {
            $this->allowDigits = false;
        }

        if (isset ($params['allowDot'])) {
            $this->allowDot = $params['allowDot'];
        } else {
            $this->allowDot = false;
        }

        if (isset ($params['allowMinus'])) {
            $this->allowMinus = $params['allowMinus'];
        } else {
            $this->allowMinus = false;
        }
    }

    /**
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $allows = '';
        if ($this->allowEnglish) {
            $allows .= 'a-zA-Z';
        }
        if ($this->allowCyrillic) {
            $allows .= '?-?';
        }
        if ($this->allowBlankSpace) {
            $allows .= ' ';
        }
        if ($this->allowBlankLine) {
            $allows .= '_';
        }
        if ($this->allowDigits) {
            $allows .= '0-9';
        }
        if ($this->allowDot) {
            $allows .= '.';
        }
        if ($this->allowMinus) {
            $allows .= '-';
        }
        return preg_replace('/[^' . $allows . ']/', '', (string)$value);
    }
}
