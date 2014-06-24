<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\System;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Sort
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    /** @var string|null */
    protected $field;

    /** @var string|null */
    protected $direction;

    /** @var string */
    protected $requestVariable = 'sort';

    /**
     * @param string $requestVariable
     */
    public function __construct($requestVariable = 'sort')
    {
        $this->requestVariable = $requestVariable;
    }

    /**
     * @param string $field
     * @return Sort
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return null|string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param $direction
     * @return Sort
     */
    public function setDirection($direction)
    {
        $direction = mb_strtoupper($direction);
        $this->direction = $direction == self::DIRECTION_DESC ? self::DIRECTION_DESC : self::DIRECTION_ASC;

        return $this;
    }

    /**
     * @return Sort
     */
    public function setFromRequest()
    {
        $sort = Application::getRequest()->get($this->requestVariable);
        $this
            ->setField(isset($sort['field']) ? $sort['field'] : null)
            ->setDirection(isset($sort['direction']) ? $sort['direction'] : null);

        return $this;
    }

    /**
     * @param $field
     * @return bool
     */
    public function isSortField($field)
    {
        return mb_strtolower($field) == mb_strtolower($this->field);
    }

    /**
     * @param string $direction
     * @return bool
     */
    public function isDirection($direction)
    {
        return mb_strtolower($direction) == mb_strtolower($this->direction);
    }

    /**
     * @return string
     */
    public function getRequestVariable()
    {
        return $this->requestVariable;
    }
}
