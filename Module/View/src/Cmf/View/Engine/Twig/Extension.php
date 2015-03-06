<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Engine\Twig;

use Cmf\System\Application;
use Cmf\View\Render\ManualRenderInterface;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Extension extends \Twig_Extension
{
    /**
     * @param string|Object $object
     * @param array $data
     * @param string $postfix
     * @return bool|string
     */
    public function render($object, array $data = [], $postfix = '')
    {
        if (!$object) {
            return '';
        }

        $result = null;
        if (is_object($object) && ($object instanceof ManualRenderInterface)) {
            $result = $object->render();
        }

        if (null === $result) {
            $view = Application::getViewProcessor();
            $result = $view->render($object, $data, $postfix);
        }

        return $result;
    }

    /**
     * @param Object | string $object instance of an any class or string with class name
     * @return \Cmf\Language\Container
     */
    public function lng($object)
    {
        return \Cmf\Language\Factory::get($object);
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'lng' => new \Twig_Function_Method($this, 'lng'),
            'render' => new \Twig_Function_Method($this, 'render', ['is_safe' => ['html']]),
            'repeat' => new \Twig_Function_Method($this, 'repeat'),
            'blockContainer' => new \Twig_Function_Method($this, 'renderBlockContainer', ['is_safe' => ['html']]),
            'var_dump' => new \Twig_Function_Function('var_dump'),
        ];
    }

    /**
     * @param string $value
     * @param int $count
     * @return string
     */
    public function repeat($value, $count)
    {
        $str = '';
        for ($i = 0; $i < $count; $i++) {
            $str .= $value;
        }

        return $str;
    }

    /**
     * @param string $containerName
     * @param array $params
     * @return string
     */
    public function renderBlockContainer($containerName, array $params = [])
    {
        return Application::getBlockManager()->renderContainer($containerName, $params);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vcmf';
    }
}
