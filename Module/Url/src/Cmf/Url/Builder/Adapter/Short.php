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
class Short implements AdapterInterface
{
    /**
     * This method generate URL for page with params $param
     *
     * @param array $params
     * @param string $anchor
     * @return string
     */
    public function build($params = [], $anchor = '')
    {
        if (!count($params)) {
            return '/';
        }

        $config = Application::getConfigManager()->loadForModule('Cmf\System', 'module');

        $defaultModule = $config->defaultModule;
        $defaultController = $config->defaultController;
        $defaultAction = $config->defaultAction;

        $defaults = ['module' => $defaultModule, 'controller' => $defaultController, 'action' => $defaultAction,];
        $params = array_merge($defaults, $params);

        $module = preg_replace('/^(Cmf\\\\)/', '', $params['module']);
        $module = str_replace('\\', '-', $module);
        $module = ltrim(mb_strtolower(preg_replace('/([A-Z])/', "_$1", $module)), '_');
        $module = str_replace('-_', '-', $module);

        $controller = ltrim(mb_strtolower(preg_replace('/([A-Z])/', "_$1", $params['controller'])), '_');
        $action = mb_strtolower($params['action']);

        if ($module == $defaultModule && $controller == $defaultController && $action == $defaultAction) {
            $url = '';
        } elseif ($controller == $defaultController && $action == $defaultAction) {
            $url = '/' . urlencode($module) . '/';
        } elseif ($action == $defaultAction) {
            $url = '/' . urlencode($module) . '/' . urlencode($controller);
        } else {
            $url = '/' . urlencode($module) . '/' . urlencode($controller) . '/' . urlencode($action);
        }

        foreach ($params as $key => $val) {
            if (in_array($key, array('module', 'controller', 'action'))) {
                continue;
            } elseif ($val instanceof Sort) {
                /** @var $sort Sort */
                $sort = $val;
                if ($field = $sort->getField()) {
                    $sortName = $sort->getRequestVariable();
                    $url .= '/' . $sortName . '~field~' . $field;
                    if ($direction = $sort->getDirection()) {
                        $url .= '/' . $sortName . '~direction~' . $direction;
                    }
                }
            } elseif ($val) {
                if ($part = $this->buildRecursive(urlencode($key), $val)) {
                    $url .= ($url ? '/' : '') . $part;
                }
            }
        }

        if (!$url) {
            $url = '/';
        }

        if ($anchor) {
            $url .= '#' . urlencode($anchor);
        }

        return $url;
    }

    /**
     * This function check for duplicate of page
     * It's need for SEO
     *
     * @param array $urlData
     * @param int $i
     * @return bool
     */
    protected function validateUrlData($urlData, $i)
    {
        $part1 = isset($urlData[$i]) ? explode('~', $urlData[$i]) : [];
        $part2 = isset($urlData[$i + 1]) ? explode('~', $urlData[$i + 1]) : [];
        $part3 = isset($urlData[$i + 2]) ? explode('~', $urlData[$i + 2]) : [];
        if (count($part1) == 1) {
            $module = $part1[0];
            if (count($part2) == 1) {
                $controller = $part2[0];
                if (count($part3) == 1) {
                    $action = $part3[0];
                } else {
                    $action = '';
                }
            } else {
                $controller = '';
                $action = '';
            }
        } else {
            $controller = '';
            $action = '';
            $module = '';
        }

        $config = Application::getConfigManager()->loadForModule('Cmf\System', 'module');

        $isDefaultModule = $module == $config->defaultModule;
        $isDefaultAction = $action == $config->defaultAction;
        $isDefaultController = ($action == '' && $controller == $config->defaultController);

        if ($isDefaultModule || $isDefaultAction || $isDefaultController) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * @param string|array $value
     * @return string|array
     */
    protected function classNameToCamelCase($value)
    {
        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = $this->classNameToCamelCase($val);
            }
        } else {
            $valueArr = explode('_', $value);
            $valueArr = array_map('mb_strtolower', $valueArr);
            $valueArr = array_map('ucfirst', $valueArr);
            $value = implode('', $valueArr);
        }

        return $value;
    }

    /**
     * @param Request $request
     * @return Short
     */
    public function initRequestVariables(Request $request)
    {
        //TODO: refactor returns and ifs
        $request->initVars(Request::TYPE_POST, $_POST);

        //delete get parameters
        $arr = explode('?', $_SERVER['REQUEST_URI']);

        $arr = explode('/', $arr[0]);
        $n = count($arr);
        if (!$arr[$n - 1]) {
            unset($arr[$n - 1]);
        }
        $n = count($arr);
        $i = 0;
        if ('' === $arr[0]) {
            unset($arr[0]);
            $i++;
        }

        if (!isset($arr[$i])) {
            return $this;
        }

        if (!$this->validateUrlData($arr, $i)) {
            $request->set('controller', 'error404');
            $request->set('module', 'error');

            return $this;
        }

        $a = explode('~', $arr[$i]);
        if (count($a) == 1) {
            $moduleName = urldecode($a[0]);
            $moduleArr = explode('-', $moduleName);

            if (1 == count($moduleArr)) {
                array_unshift($moduleArr, 'Cmf');
            } elseif ('cmf' == $moduleArr[0]) {
                $request->set('controller', 'error404');
                $request->set('module', 'error');

                return $this;
            }
            $moduleArr = $this->classNameToCamelCase($moduleArr);
            $moduleName = implode('\\', $moduleArr);

            $request->set('module', $moduleName, Request::TYPE_GET);
            unset($arr[$i]);
            $i++;
            if (!isset($arr[$i])) {
                return $this;
            }
            $a = explode('~', $arr[$i]);
            if (count($a) == 1) {
                $request->set('controller', $this->classNameToCamelCase(urldecode($a[0])), Request::TYPE_GET);
                unset($arr[$i]);
                $i++;
                if (!isset($arr[$i])) {
                    return $this;
                }
                $a = explode('~', $arr[$i]);
                if (count($a) == 1) {
                    $request->set('action', urldecode($a[0]), Request::TYPE_GET);
                    unset($arr[$i]);
                    $i++;
                }
            }
        }

        $config = Application::getConfigManager()->loadForModule('Cmf\System', 'module');

        if (!$request->get('module', Request::TYPE_GET)) {
            $request->set('module', $config->defaultModule, Request::TYPE_GET);
        }
        if (!$request->get('controller', Request::TYPE_GET)) {
            $request->set('controller', $config->defaultController, Request::TYPE_GET);
        }
        if (!$request->get('action', Request::TYPE_GET)) {
            $request->set('action', $config->defaultAction, Request::TYPE_GET);
        }

        for (; $i < $n; $i++) {
            $a = explode('~', $arr[$i]);
            $m = count($a);
            if ($m < 2) {
                $request->set('controller', 'error404');

                return $this;
            } elseif ($m == 2) {
                $request->set(urldecode($a[0]), urldecode($a[1]), Request::TYPE_GET);
            } else {
                $names = $request->get(urldecode($a[0]), Request::TYPE_GET);
                if (!is_array($names)) {
                    $names = [];
                }
                $link = & $names;
                for ($j = 1; $j < $m - 1; $j++) {
                    $key = urlencode($a[$j]);
                    $link[$key] = [];
                    $link = & $link[$key];
                    if ($j == $m - 2) {
                        $link = urlencode($a[$j + 1]);
                        $request->set(urldecode($a[0]), $names, Request::TYPE_GET);
                    }
                }

            }
        }

        return $this;
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
                if ($part = $this->buildRecursive($key . '~' . urlencode($key2), $val)) {
                    $url .= ($url ? '/' : '') . $part;
                }
            }
        } else {
            $url .= ($url ? '/' : '') . $key . '~' . urlencode($value);
        }

        return $url;
    }
}
