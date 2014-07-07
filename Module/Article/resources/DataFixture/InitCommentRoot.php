<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\Article;

use Cmf\Article\Model\Entity\Comment;
use Cmf\System\Application;
use Cmf\User\Model\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Initialization of the root row on database for article comment table
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class InitCommentRoot extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 30;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $em = Application::getEntityManager();
        $isInitialized = $em->getRepository('Cmf\Article\Model\Entity\Comment')->findOneBy([]);

        if (!$isInitialized) {
            $root = new Comment();
            /** @var User $user */
            $user = $this->getReference('cmf-user-admin');
            $root
                ->setText('root')
                ->setUser($user);

            $manager->persist($root);
            $manager->flush();
        }
    }
}
