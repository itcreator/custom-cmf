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
 * Twig adapter
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Twig extends AbstractEngine
{
    /** @var string */
    protected $extension = '.twig';

    /** @var  \Twig_Environment */
    protected $twig;

    public function __construct()
    {
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem('/');
        $twigParams = [
            'cache' => sprintf('%stmp/%s/Twig/_cache', ROOT, ThemeFactory::THEME_FOLDER),
            'debug' => 'development' == APPLICATION_MODE,
        ];
        $this->twig = new \Twig_Environment($loader, $twigParams);

        $this->twig->addExtension(new \Cmf\View\Engine\Twig\Extension());
    }

    /**
     * @param string $template
     * @param array $values
     * @return string
     */
    public function render($template, array $values = [])
    {
        return $this->twig->render($template . $this->extension, $values);
    }
}
