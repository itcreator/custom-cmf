<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\User\DataFixture;

use Cmf\Permission\Model\Entity\Role;
use Cmf\System\Application;
use Cmf\User\Model\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NoResultException;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * @param ObjectManager $manager
     * @return $this
     */
    protected function loadGuest(ObjectManager $manager)
    {
        $em = Application::getEntityManager();
        $userRep = $em->getRepository('Cmf\User\Model\Entity\User');
        $guest = $userRep->findOneBy(['type' => User::TYPE_SYSTEM, 'userName' => User::GUEST_USERNAME]);

        if (!$guest) {
            /** @var Role $guestRole */
            $guestRole = $this->getReference('cmf-permission-role-guest');
            $guest = new User();
            $guest
                ->setEmail('')
                ->setUserName(User::GUEST_USERNAME)
                ->setStatus(User::STATUS_ACTIVE)
                ->setType(User::TYPE_SYSTEM)
                ->setPassword('')
                ->setRegistrationTime(time())
                ->addRole($guestRole);

            $manager->persist($guest);
            $manager->flush();
        }

        $this->setReference('cmf-user-guest', $guest);

        return $this;
    }

    /**
     * @param ObjectManager $manager
     * @return $this
     */
    protected function loadAdmin(ObjectManager $manager)
    {
        $em = Application::getEntityManager();
        $userRep = $em->getRepository('Cmf\User\Model\Entity\User');
        $qb = $userRep->createQueryBuilder('u');
        $qb
            ->join('u.roles', 'r')
            ->andWhere('r.name = :roleName')
            ->setParameter('roleName', Role::ROLE_ADMIN)
        ;

        try {
            $admin = $qb->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            /** @var Role $adminRole */
            $adminRole = $this->getReference('cmf-permission-role-admin');
            $admin = new User();
            $admin
                ->setEmail('')
                ->setUserName('admin')
                ->setStatus(User::STATUS_ACTIVE)
                ->setPassword(1)
                ->setRegistrationTime(time())
                ->addRole($adminRole)
            ;
            $manager->persist($admin);
            $manager->flush();
        }

        $this->setReference('cmf-user-admin', $admin);

        return $this;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this
            ->loadGuest($manager)
            ->loadAdmin($manager);
    }
}
