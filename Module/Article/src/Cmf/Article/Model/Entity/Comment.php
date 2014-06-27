<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Entity for comment of the articles
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @method Article getContent()
 *
 * @ORM\Table(name="article_comment")
 * @ORM\Entity(repositoryClass="Cmf\Comment\Model\Repository\CommentRepository")
 * @Gedmo\Tree(type="materializedPath")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Comment extends \Cmf\Comment\Model\Entity\Comment
{
    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     **/
    protected $content;
}
