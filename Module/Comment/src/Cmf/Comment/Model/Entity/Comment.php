<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment\Model\Entity;

use Cmf\Db\BaseEntity;
use Cmf\GedmoExtension\Tree\Traits\MaterializedPathEntity;
use Cmf\System\Application;
use Cmf\User\Model\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\IpTraceable\Traits\IpTraceableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Abstract entity for comments
 *
 * @ORM\MappedSuperclass
 * @ORM\Entity(repositoryClass="Cmf\Comment\Model\Repository\Coment")
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Tree(type="materializedPath")
 * @Gedmo\IpTraceable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
abstract class Comment extends BaseEntity
{
    use IpTraceableEntity;
    use SoftDeleteableEntity;
    use MaterializedPathEntity;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Gedmo\TreePathSource
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    protected $text;

    /**
     * @var int
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    protected $userId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Cmf\User\Model\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $userName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $email;

    /**
     * @var BaseEntity Annotation must be extended
     */
    protected $content;

    /**
     * @var int
     * @ORM\Column(name="content_id", type="integer", nullable=true)
     */
    protected $contentId;

    /**
     * @var $this | null
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     */
    protected $children;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        if (!$this->email) {
            $this->email = $user->getEmail();
        }

        if (!$this->userName) {
            $this->userName = $user->getUserName();
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setUserName($name)
    {
        $this->userName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return BaseEntity
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param BaseEntity $content
     * @return $this
     */
    public function setContent(BaseEntity $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if ($this->user) {
            return;
        }

        $em = Application::getEntityManager();
        /** @var $repository \Cmf\User\Model\Repository\UserRepository */
        $repository = $em->getRepository('User\Model\Repository\UserRepository');

        $this->setUser($repository->getGuest());
    }
}
