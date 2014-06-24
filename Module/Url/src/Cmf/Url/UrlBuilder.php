<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Url;

use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class UrlBuilder
{
    /** @var \Cmf\Url\Builder\Adapter\AdapterInterface */
    protected $adapter;

    /** @var bool */
    protected $isInitialized = false;

    /**
     * Initialization of a builder
     */
    public function __construct()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\Url');
        $className = $config->adapter;

        if (!Application::getClassLoader()->findFile($className)) {
            throw new AdapterNotFoundException(sprintf('Can not load adapter "%s" for url builder.', $className));
        }

        $this->adapter = new $className();
    }

    /**
     * @param array $params
     * @param string $anchor
     * @return string
     */
    public function build($params = [], $anchor = '')
    {
        return $this->adapter->build($params, $anchor);
    }

    /**
     * Method return  url adapter
     *
     * @return \Cmf\Url\Builder\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
