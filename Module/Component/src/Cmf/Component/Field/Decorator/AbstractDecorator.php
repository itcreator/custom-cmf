<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Field\Decorator;

use Cmf\Component\Field\AbstractField;

/**
 * Abstract decorator for fields
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractDecorator
{
    /** @var AbstractField */
    protected $field;

    /**
     * @param AbstractField $field
     */
    public function __construct(AbstractField $field)
    {
        $this->field = $field;
    }

    /**
     * @return AbstractField
     */
    public function getField()
    {
        return $this->field;
    }
}
