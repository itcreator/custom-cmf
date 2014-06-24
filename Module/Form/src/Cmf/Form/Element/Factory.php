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
class Factory
{
    /**
     * @static
     * @param $params
     * @return AbstractElement
     * @throws \Cmf\Form\Exception
     */
    public static function create($params)
    {
        $input = isset($params['input']) ? $params['input'] : 'Text';
        if (class_exists($input)) {
            $element =  new $input($params);
        } else {
            $className = '\Cmf\Form\Element\\' . $input;
            if (class_exists($className)) {
                $element = new $className($params);
            } else {
                throw new \Cmf\Form\Exception('Not found class for type "' . $input . '"');
            }
        }

        return $element;
    }
}
