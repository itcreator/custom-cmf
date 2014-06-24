<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Url\Builder\Adapter;

use Cmf\System\Request;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
interface AdapterInterface
{
    /**
     * @param array $params
     * @param string $anchor
     * @return string
     */
    public function build($params = [], $anchor = '');

    /**
     * @param Request $request
     * @return AdapterInterface
     */
    public function initRequestVariables(Request $request);
}
