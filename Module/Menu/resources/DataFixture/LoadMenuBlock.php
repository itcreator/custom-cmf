<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\Menu\DataFixture;

use Cmf\Block\Model\Entity\Block;
use Cmf\System\Application;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class LoadMenuBlock extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 120;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $blockName = 'menu';
        $class = 'Cmf\Menu\Block\MenuBlock';

        $em = Application::getEntityManager();
        $blockRep = $em->getRepository('Cmf\Block\Model\Entity\Block');
        $block = $blockRep->findOneBy([
            'name' => $blockName,
            'class' => $class,
        ]);

        if (!$block) {
            $block = new Block();
            $block
                ->setName($blockName)
                ->setClass($class);

            $manager->persist($block);
            $manager->flush();
        }

        $this->addReference('cmf-block-block-' . $class . '-' . $blockName, $block);
    }
}
