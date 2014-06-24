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
 * Decorator for link to record
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ItemLink extends AbstractDecorator
{
    public function getUrl()
    {
        $urlParams = $this->field->getConfig()->urlParams;
        $urlParams = $urlParams ? $urlParams->toArray() : [];
        $fields = $this->field->getConfig()->fields;

        if ($fields) {
            $fields = $fields->toArray();
            foreach ($fields as $name => $field) {
                if ($row = $this->field->getRow()) {
                    $urlParams[$name] = $row->getRawField($field);
                } elseif ($collection = $this->field->getEntity()) {
                    $getter = 'get' . $name;
                    $urlParams[$name] = $collection->$getter();
                }
            }
        }

        return Application::getUrlBuilder()->build($urlParams);
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isCurrentUrl($url)
    {
        return $_SERVER['REQUEST_URI'] == $url;
    }
}
