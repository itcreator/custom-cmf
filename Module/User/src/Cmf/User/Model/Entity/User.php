<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Model\Entity;

use Cmf\Db\BaseEntity;
use Cmf\Permission\Model\Entity\Role;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="user_user")
 * @ORM\Entity(repositoryClass="Cmf\User\Model\Repository\UserRepository")
 **/
class User extends BaseEntity
{
    const TYPE_GENERAL = 1;
    const TYPE_SYSTEM = 2;

    const GUEST_USERNAME = 'guest';

    const STATUS_NOT_CONFIRMED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;
    const STATUS_DELETED = 3;

    /**
     * @var string $userName
     *
     * @ORM\Column(name="userName", type="string", length=80)
     */
    protected $userName;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="firstName", type="string", length=80, nullable=true)
     */
    protected $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="lastName", type="string", length=80, nullable=true)
     */
    protected $lastName;

    /**
     * @var string $fatherName
     *
     * @ORM\Column(name="fatherName", type="string", length=80, nullable=true)
     */
    protected $fatherName;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    protected $password;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var string $activationCode
     *
     * @ORM\Column(name="activationCode", type="string", length=100, nullable=true)
     */
    protected $activationCode;

    /**
     * @var integer $registrationTime
     *
     * @ORM\Column(name="registrationTime", type="integer")
     */
    protected $registrationTime;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Address", inversedBy="user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="address_id", referencedColumnName="id", unique=true)
     * })
     */
    protected $address;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default" = 1})
     */
    protected $type = self::TYPE_GENERAL;


    /**
     * @var ArrayCollection|Role[]
     * @ORM\ManyToMany(targetEntity="Cmf\Permission\Model\Entity\Role", mappedBy="users")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return User
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set fatherName
     *
     * @param string $fatherName
     * @return User
     */
    public function setFatherName($fatherName)
    {
        $this->fatherName = $fatherName;

        return $this;
    }

    /**
     * Get fatherName
     *
     * @return string
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set activationCode
     *
     * @param string $activationCode
     * @return User
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    /**
     * Get activationCode
     *
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * Set registrationTime
     *
     * @param integer $registrationTime
     * @return User
     */
    public function setRegistrationTime($registrationTime)
    {
        $this->registrationTime = $registrationTime;

        return $this;
    }

    /**
     * Get registrationTime
     *
     * @return integer
     */
    public function getRegistrationTime()
    {
        return $this->registrationTime;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return User
     */
    public function setAddress(Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return self::TYPE_SYSTEM == $this->type && self::GUEST_USERNAME == $this->userName;
    }

    /**
     * @return ArrayCollection|\Cmf\Permission\Model\Entity\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
        $role->addUser($this);

        return $this;
    }
}
