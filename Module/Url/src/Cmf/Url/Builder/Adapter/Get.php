<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */


namespace Cmf\Url\Builder\Adapter;

use Cmf\System\Application;
use Cmf\System\Request;
use Cmf\System\Sort;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Get implements AdapterInterface
{
    /**
     * @param array $params
     * @param string $anchor
     * @return string
     */
    public function build($params = [], $anchor = '')
    {
        if (!count($params)) {
            return '/';
        }

        if (isset($params['controller']) && $params['controller']) {
            $module = $params['controller'];
            $url = '/?controller=' . urlencode($module);
            $separator = '&';
        } else {
            $separator = '?';
            $url = '';
        }

        if (isset($params['action']) && $params['action']) {
            $action = $params['action'];
            $url .= $separator . 'action=' . urlencode($action);
        }

        foreach ($params as $key => $val) {
            if ('controller' == $key || 'action' == $key) {
                continue;
            } elseif ($val instanceof Sort) {

                /** @var $sort \Cmf\System\Sort */
                $sort = $val;
                if ($field = $sort->getField()) {
                    $sortName = $sort->getRequestVariable();
                    $url .= ($url ? '&' : '') . $sortName . '[field]=' . $field;
                    if ($direction = $sort->getDirection()) {
                        $url .= '&' . $sortName . '[direction]=' . $direction;
                    }
                }
            } elseif ($val) {
                if ($part = $this->buildRecursive($key, $val)) {
                    $url .= ($url ? '&' : '') . $part;
                }
            }
        }
        if ($anchor) {
            $url .= '#' . urlencode($anchor);
        }

        return $url;
    }

    /**
     *  arrays in request
     *
     * @param string $key
     * @param string|array $value
     * @return string
     */
    protected function buildRecursive($key, $value)
    {
        $url = '';
        if (is_array($value)) {
            foreach ($value as $key2 => $val) {
                if ($part = $this->buildRecursive($key . '[' . $key2 . ']', $val)) {
                    $url .= ($url ? '&' : '') . $part;
                }
            }
        } else {
            $url .= ($url ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }

        return $url;
    }

    /**
     * @param Request $request
     * @return Get
     */
    public function initRequestVariables(Request $request)
    {
        $request->initVars(Request::TYPE_GET, $_GET);
        $request->initVars(Request::TYPE_POST, $_POST);
        $request->initVars(Request::TYPE_ANY, $_REQUEST);

        return $this;
    }
}
