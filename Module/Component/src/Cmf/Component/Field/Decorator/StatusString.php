<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
namespace Cmf\Component\Field\Decorator;

/**
 * Decorator for status
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class StatusString extends AbstractDecorator
{
    /**
     * @return string
     */
    public function getStatusTitle()
    {
        $statuses = $this->field->getConfig()->dataSource;
        $value = $this->field->getValue();

        if ($statuses instanceof \Cmf\Data\Source\ArraySource) {
            if (false === $title = $statuses->getDataItemTitle($value)) {
                $title = $value;
            }
        } else {
            $title = $value;
        }

        return $title;
    }
}
