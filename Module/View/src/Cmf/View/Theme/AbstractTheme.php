<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Theme;

use Cmf\System\Application;
use Cmf\View\Theme\Exception\TemplateNotFoundException;

/**
 * Abstract theme
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractTheme
{
    /** @var string */
    protected $parentThemeName;

    /** @var string */
    protected $themeName;

    /** @var array  */
    protected $paths = [];

    /**
     * @param mixed $object
     * @return mixed
     */
    abstract protected function getEngine($object = null);

    /**
     * @param string $className
     * @param string $postfix
     * @return bool|string
     */
    abstract protected function searchIterative($className, $postfix = '');

    /**
     * @param string $className
     * @param string $postfix
     * @return bool
     */
    abstract protected function searchForCurrentModule($className, $postfix = '');

    /**
     * @param string|object $object string or object
     * @param array $data
     * @param string $postfix
     * @return string
     * @throws TemplateNotFoundException
     */
    public function render($object, array $data = [], $postfix = '')
    {
        if ($path = $this->getTemplatePath($object, $postfix)) {
            //search template in current theme
            $data = array_merge($data, ['this' => Application::getViewProcessor(), 'object' => $object]);

            $result = $this->getEngine($path)->render($path, $data);
        } else {
            //render parent theme
            if (!$this->parentThemeName) {
                $object = is_object($object) ? get_class($object) : $object;

                throw new TemplateNotFoundException(sprintf('Template %s not found', $object));
            }
            $parentTheme = ThemeFactory::getTheme($this->parentThemeName);

            $result = $parentTheme->render($object, $data, $postfix);
        }

        return $result;
    }

    /**
     * @param Object|string $object
     * @param string $postfix
     * @return string
     */
    protected function getTemplatePath($object, $postfix = '')
    {
        if (is_object($object)) {
            $object = get_class($object);
        }
        $path = $object;
        $key = $path . $postfix;

        if (isset($this->paths[$key])) {
            return $this->paths[$key];
        }

        if (false !== $result = $this->searchForCurrentModule($object, $postfix)) {
            $this->paths[$key] = $result;
        } elseif (false !== $result = $this->searchIterative($object, $postfix)) {
            $this->paths[$key] = $result;
        } else {
            $this->paths[$key] = false;
        }

        return $this->paths[$key];
    }
}
