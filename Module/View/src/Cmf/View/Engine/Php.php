<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Engine;

use Cmf\Structure\Collection\AssociateCollection;

/**
 * Adapter for PHP templates
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Php extends AbstractEngine
{
    /** @var array  array cache for templates */
    protected $included = [];

    /** @var string */
    protected $extension = '.php';

    /** @var */
    protected $folder;

    public function __construct()
    {
        $this->folder = '/';
    }

    /**
     * @param string $template
     * @return string
     */
    protected function getFullFileName($template)
    {
        return $this->folder . $template . $this->extension;
    }

    /**
     * @param \Closure $function
     * @param array $values
     * @return string
     */
    public function process(\Closure $function, array $values = [])
    {
        $collection = new AssociateCollection();
        $collection->setItems($values);
        ob_start();
        $function($collection);

        return ob_get_clean();
    }

    /**
     * @param string $template
     * @param array $values
     * @return string
     * @throws \Cmf\View\Exception if file is not found or bad
     */
    public function render($template, array $values = [])
    {
        $template = ltrim($template, '/\\');
        if (isset($this->included[$template])) {
            $func = $this->included[$template];

            return $this->process($func, $values);
        } else {
            $fullName = $this->getFullFileName($template);
            if (is_file($fullName)) {
                $func = include($this->getFullFileName($template));
                if ($func instanceof \Closure) {
                    $this->included[$template] = $func;

                    return $this->process($func, $values);
                } else {
                    throw new \Cmf\View\Exception(sprintf('Template "%s" is bad.', $template));
                }
            } else {
                throw new \Cmf\View\Exception(sprintf('Template "%s" is not found', $template));
            }
        }
    }
}
