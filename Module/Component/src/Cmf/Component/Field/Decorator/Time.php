<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Field\Decorator;

use Cmf\System\Application;

/**
 * Decorator for time
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Time extends AbstractDecorator
{
    public function getFormattedTime()
    {
        if (!($format = $this->field->getConfig()->format)) {
            $format = Application::getConfigManager()->loadForModule('Cmf\System', 'dateTime')->timeFormat;
        }

        $dateTime = $this->field->getValue();
        if ($dateTime instanceof \DateTime) {
            $dateTime = $dateTime->format($format);
        } else {
            $dateTime = date($format, $dateTime);
        }

        return $dateTime;
    }
}
