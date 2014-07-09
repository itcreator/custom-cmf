<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\LoadBlockData\DataFixture;

use Cmf\Block\Model\Entity\Binding;
use Cmf\Block\Model\Entity\Block;
use Cmf\Block\Model\Entity\Container;
use Cmf\System\Application;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Initialization of the blocks
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class LoadBlockData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 130;
    }
    /**
     * @param ObjectManager $manager
     * @param string $containerName
     * @return Container
     */
    protected function loadBlockContainer(ObjectManager $manager, $containerName)
    {
        $em = Application::getEntityManager();
        $container = $em->getRepository('Cmf\Block\Model\Entity\Container')->findOneBy(['name' => $containerName]);

        if (!$container) {
            $container = new Container();
            $container->setName($containerName);

            $manager->persist($container);
            $manager->flush();
        }

        $this->addReference('cmf-block-container-' . $containerName, $container);

        return $container;
    }

    /**
     * @param ObjectManager $manager
     * @param Container $container
     * @param Block $block
     * @param array $controllerData
     * @return $this
     */
    protected function loadBinding(ObjectManager $manager, Container $container, Block $block, array $controllerData)
    {
        $em = Application::getEntityManager();
        $bindingRep = $em->getRepository('Cmf\Block\Model\Entity\Binding');
        $binding = $bindingRep->findOneBy([
            'block' => $block,
            'container' => $container,
            'params' => json_encode($controllerData['params']),
            'moduleName' => $controllerData['moduleName'],
            'controllerName' => $controllerData['controllerName'],
        ]);

        if (!$binding) {
            $binding = new Binding();
            $binding
                ->setBlock($block)
                ->setContainer($container)
                ->setParams($controllerData['params'])
                ->setModuleName($controllerData['moduleName'])
                ->setControllerName($controllerData['controllerName']);

            $manager->persist($binding);
            $manager->flush();
        }

        return $this;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $top = $this->loadBlockContainer($manager, 'top');
        $this->loadBlockContainer($manager, 'bottom');
        $this->loadBlockContainer($manager, 'after-content');


        //TODO: refactor binding
        $controllers = [
            ['moduleName' => 'Cmf\Index', 'controllerName' => 'Index'],
            ['moduleName' => 'Cmf\User', 'controllerName' => 'User'],
            ['moduleName' => 'Cmf\Article', 'controllerName' => 'Article'],
            ['moduleName' => 'Cmf\Article', 'controllerName' => 'Category'],
            ['moduleName' => 'Cmf\Error', 'controllerName' => 'Error404'],
        ];

        /** @var Block $menu */
        $menu = $this->getReference('cmf-block-block-Cmf\Menu\Block\MenuBlock-menu');
        /** @var Block $auth */
        $auth = $this->getReference('cmf-block-block-Cmf\User\Block\AuthBlock-auth');
        foreach ($controllers as $controllerData) {
            $params = ['params' => []];
            $this->loadBinding($manager, $top, $auth, array_merge($controllerData, $params));
            $params = ['params' => ['menuName' => 'main']];
            $this->loadBinding($manager, $top, $menu, array_merge($controllerData, $params));
        }
    }
}
