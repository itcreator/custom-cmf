<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Field\Decorator;

use Cmf\Db\BaseEntity;
use Cmf\System\Application;

/**
 * Decorator for link to record
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ItemLinkCollection extends AbstractDecorator
{
    /**
     * @param BaseEntity $category Entity
     * @return string
     */
    public function getUrl(BaseEntity $category)
    {
        $config = $this->field->getConfig()->foreign;
        $urlParams = $config->urlParams;
        $urlParams = $urlParams ? $urlParams->toArray() : [];
        $fields = $config->fields;

        if ($fields) {
            $fields = $fields->toArray();
            foreach ($fields as $name => $field) {
                $getter = 'get' . $name;
                $urlParams[$name] = $category->$getter();
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
