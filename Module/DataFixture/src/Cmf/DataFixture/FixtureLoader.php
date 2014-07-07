<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\DataFixture;

use Cmf\System\Application;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class FixtureLoader
{
    /**
     * @return $this
     */
    public function load()
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\DataFixture', 'fixturePath');

        $loader = new Loader();
        $mm = Application::getModuleManager();

        if (count($config)) {
            foreach ($config->toArray() as $moduleName) {
                $modulePath = $mm->getModulePath($moduleName);
                $loader->loadFromDirectory($modulePath . '/resources/DataFixture');
            }
        }

        $executor = new ORMExecutor(Application::getEntityManager());
        $executor->execute($loader->getFixtures(), true);

        return $this;
    }
}
