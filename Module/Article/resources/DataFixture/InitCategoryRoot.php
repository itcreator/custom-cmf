<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\Article;

use Cmf\Article\Model\Entity\Category;
use Cmf\System\Application;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Initialization of the root row on database for article category table
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class InitCategoryRoot implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $em = Application::getEntityManager();
        $isInitialized = $em->getRepository('Cmf\Article\Model\Entity\Category')->findOneBy([]);

        if (!$isInitialized) {
            $root = new Category();

            $root->setTitle('root')
                ->setDescription('It is system root node')
            ;

            $manager->persist($root);
            $manager->flush();
        }
    }
}
