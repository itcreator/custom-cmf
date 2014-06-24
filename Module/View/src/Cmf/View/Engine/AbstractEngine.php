<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Engine;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
abstract class AbstractEngine
{
    /** @var string */
    protected $extension = '.tpl';

    /**
     * @param string $template
     * @param array $values
     * @return string
     */
    abstract public function render($template, array $values = []);

    /**
     * @return string extension of template file
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Method return true if template file exist
     *
     * @param string $templateFile
     * @return bool
     */
    public function templateExist($templateFile)
    {
        $file = $templateFile . $this->extension;

        return file_exists($file);
    }
}
