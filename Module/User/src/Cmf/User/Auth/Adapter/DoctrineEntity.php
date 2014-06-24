<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\User\Auth\Adapter;

use Doctrine\ORM\EntityManager;
use Cmf\User\Model\Entity\User;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class DoctrineEntity extends AbstractAdapter
{
    /** @var User */
    protected $entity;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param User $entity
     */
    public function __construct(EntityManager $entityManager, User $entity)
    {
        $this->entityManager = $entityManager;
        $this->setEntity($entity);
    }

    /**
     * @param User $entity
     * @return DoctrineEntity
     */
    public function setEntity(User $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return User
     */
    public function authenticate()
    {
        return $this->entity;
    }
}
