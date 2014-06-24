<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Captcha;

use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Captcha
{
    /** @var string */
    protected $value;

    /** @var string */
    protected $xorValue;

    /** @var array */
    protected $digits = [];

    /** @var \Zend\Config\Config */
    protected $config;

    public function __construct()
    {
        $this->config = Application::getConfigManager()->loadForModule('Cmf\Captcha');
    }

    /**
     * generate captcha value
     * @return Captcha
     */
    public function generateCode()
    {
        $this->digits[0] = rand(0, 9);
        $this->digits[1] = rand(0, 9);
        $this->digits[2] = rand(0, 9);
        $this->digits[3] = rand(0, 9);
        $this->digits[4] = rand(0, 9);
        $this->value = $this->digits[0] * 10000 + $this->digits[1] * 1000;
        $this->value += $this->digits[2] * 100 + $this->digits[3] * 10 + $this->digits[4];
        $this->xorValue = $this->value ^ $this->config->codeKey;
        $_SESSION['captcha'] = $this->xorValue;

        return $this;
    }

    /**
     * @return Captcha
     */
    public function loadValueFromSession()
    {
        $this->xorValue = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : null;
        $this->value = $this->xorValue ^ $this->config->codeKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getDigits()
    {
        return $this->digits;
    }
}
