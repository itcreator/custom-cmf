<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\Form\Element;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Submit extends Input
{
    /** @var boolean */
    protected $loadingIsEnabled = false;

    /** @var int */
    protected $weight = 1000;

    /**
     * @param array $params
     */
    public function __construct($params = [])
    {
        parent::__construct($params);

        $this->attributes->type = 'submit';
        $this->attributes->name = 'submit';
    }
}
