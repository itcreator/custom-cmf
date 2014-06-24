<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\View;

use Cmf\System\Application;
use Cmf\View\Engine\AbstractEngine;
use Cmf\View\Helper\HelperFactory;
use Cmf\View\Theme\AbstractTheme;
use Cmf\View\Theme\CustomTheme;
use Cmf\View\Theme\ThemeFactory;

/**
 * Processing of a templates
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ViewProcessor
{

    /**
     * @param Object | string $object any instance of a class or string with class name
     * @param array $data
     * @param string $postfix
     * @return bool|string
     */
    public function render($object, array $data = [], $postfix = '')
    {
        $cm = Application::getConfigManager();
        $config = $cm->loadForModule('Cmf\View');
        $theme = ThemeFactory::getTheme($config->currentTheme);

        return $theme->render($object, $data, $postfix);
    }

    /**
     * Method for getting of a view helper in template file
     *
     * @param string $helperName
     * @return \Cmf\View\Helper\AbstractHelper
     */
    public function getHelper($helperName)
    {
        return HelperFactory::get($helperName);
    }
}
