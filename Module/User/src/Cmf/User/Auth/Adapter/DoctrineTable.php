<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */


namespace Cmf\User\Auth\Adapter;

use Cmf\User\Model\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

class DoctrineTable extends AbstractAdapter
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager = null;

    /** @var string */
    protected $login = '';

    /** @var string */
    protected $password = '';

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $login
     * @return DoctrineTable
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @param string $password
     * @return DoctrineTable
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @return User
     */
    public function authenticate()
    {
        return $this->entityManager
            ->getRepository('Cmf\User\Model\Entity\User')
            ->findOneBy([
                'userName' => $this->login,
                'password' => $this->password,
            ]);
    }
}
