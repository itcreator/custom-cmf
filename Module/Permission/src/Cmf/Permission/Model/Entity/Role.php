<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Permission\Model\Entity;

use Cmf\Db\BaseEntity;
use Cmf\User\Model\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for user roles
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="permission_role")
 * @ORM\Entity
 **/
class Role extends BaseEntity
{
    const ROLE_GUEST = 'guest';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="Cmf\User\Model\Entity\User", inversedBy="roles")
     * @ORM\JoinTable(name="permission_user_2_role")
     */
    private $users;

    //TODO: change to unidirectional

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }
}
