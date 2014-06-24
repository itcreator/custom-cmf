<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View\Engine;

use Cmf\View\Theme\ThemeFactory;

/**
 * Smarty adapter
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Smarty extends AbstractEngine
{
    /** @var \Smarty */
    protected $smarty;

    public function __construct()
    {
        $this->smarty = new \Smarty;
        $this->smarty->caching = false;
        $this->smarty->debugging = true;
        $this->smarty->error_reporting = E_ALL | !E_NOTICE;
        //$this->smarty->error_reporting = E_ALL & !E_NOTICE;

        $tmpPath = sprintf('%stmp/%s/Smarty/', ROOT, ThemeFactory::THEME_FOLDER);

        $this->smarty->setTemplateDir('/');
        $this->smarty->setCompileDir($tmpPath . '_compile/');
        $this->smarty->setCacheDir($tmpPath . '_cache/');
    }

    /**
     * @param string $template
     * @param array $values
     * @return string
     */
    public function render($template, array $values = [])
    {

        $this->smarty->assign($values);
        $result = $this->smarty->fetch($template . $this->extension);

        //TODO: раскоментировать, когда смарти научится нормально удалять переменные
        //$this->smarty->clearAssign($values);

        return $result;
    }
}
