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

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="addresses")
 * @ORM\Entity
 */
class Address extends BaseEntity
{
    /**
     * @var string $street
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User", mappedBy="address")
     */
    private $user;


    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Address
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
