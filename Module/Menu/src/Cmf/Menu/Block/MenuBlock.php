<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Menu\Block;

use Cmf\Block\AbstractBlock;
use Cmf\System\Application;
use Cmf\View\Helper\HelperFactory;

/**
 * Block for menu
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class MenuBlock extends AbstractBlock
{
    /** @var array  */
    protected $params = [
        'menuName' => 'main',
    ];

    /**
     * @return array|AbstractBlock
     */
    public function handle()
    {
        //TODO: move it files from here. Use requireJS
        HelperFactory::getJS()->addScript('frameworks/jquery/jquery.min.js');
        HelperFactory::getJS()->addScript('Twitter/Bootstrap/js/bootstrap.js');

        return [
            'menu' => Application::getMenuManager()->getMenu($this->params['menuName']),
        ];
    }
}
