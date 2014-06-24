<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Model\Repository;

use Cmf\User\Model\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Repository for User entity
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * @return string
     */
    public static function generateActivationCode()
    {
        $code = md5(rand(10, 999999999999) . ';' . $_SERVER['REMOTE_ADDR']);
        $code .= md5(microtime(true));

        return $code;
    }

    /**
     * @return User|null
     */
    public function getGuest()
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.type=:type')
            ->andWhere('u.userName=:userName')
            ->setParameters([
                'type' => User::TYPE_SYSTEM,
                'userName' => User::GUEST_USERNAME,
            ]);

        return $qb->getQuery()->getSingleResult();
    }
}
