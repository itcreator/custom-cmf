<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
 
namespace Cmf\Permission\DataFixture;

use Cmf\Permission\Model\Entity\Role;
use Cmf\System\Application;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class LoadRolesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param ObjectManager $manager
     * @param string $roleName
     * @return $this
     */
    protected function loadRole(ObjectManager $manager, $roleName)
    {
        $em = Application::getEntityManager();
        $role = $em->getRepository('Cmf\Permission\Model\Entity\Role')->findOneBy(['name' => $roleName]);

        if (!$role) {
            $role = new Role();
            $role->setName($roleName);

            $manager->persist($role);
            $manager->flush();
        }

        $this->addReference('cmf-permission-role-' . $roleName, $role);

        return $this;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this
            ->loadRole($manager, Role::ROLE_GUEST)
            ->loadRole($manager, Role::ROLE_USER)
            ->loadRole($manager, Role::ROLE_ADMIN);
    }
}
