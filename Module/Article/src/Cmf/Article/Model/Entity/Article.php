<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\Entity;

use Cmf\Db\BaseEntity;
use Cmf\User\Model\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="article_article")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Article extends BaseEntity
{
    const STATUS_NOT_MODERATED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;
    const STATUS_DELETED = 3;

    use SoftDeleteableEntity;

    /**
     * @var |Category[]
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinTable(name="article_2_category")
     */
    protected $categories;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @var string $summary
     *
     * @ORM\Column(name="summary", type="text", nullable=true)
     */
    protected $summary;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

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
     * @var int
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    protected $userId;

    /**
     * @var Collection|Comment[]
     * @ORM\OneToMany(targetEntity="Cmf\Article\Model\Entity\Comment", mappedBy="content")
     **/
    protected $comments;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * For adding article to category use only $category->addArticle($article)
     *
     * @param Category $category
     * @return Article
     */
    public function addToCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @param Collection $categories
     * @return $this
     */
    public function setCategories(Collection $categories = null)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection|PersistentCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Article
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Article
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Article
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
     * Set user
     *
     * @param User $user
     * @return Article
     */
    public function setUser(User $user)
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
