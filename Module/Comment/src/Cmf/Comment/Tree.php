<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Comment;

use Cmf\Comment\Model\ActionLink\CommentConfig;
use Cmf\Comment\Model\Entity\Comment;
use Cmf\Component\Link\ActionLink;
use Cmf\System\Application;

/**
 * Class for displaying of a comments tree
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Tree
{
    /** @var string  */
    protected $commentEntityName;

    /** @var  int */
    protected $idContent;

    public function __construct($commentEntityName, $idContent)
    {
        $this->commentEntityName = $commentEntityName;
        $this->idContent = $idContent;
    }

    /**
     * @param int $idContent
     * @param string $moduleName
     * @return CommentConfig
     */
    protected function getActionLinkConfig($idContent, $moduleName)
    {
        $config = Application::getConfigManager()->loadForModule('Cmf\Comment', 'actionLink');
        $className = $config->configClass;

        return new $className($idContent, $moduleName);
    }

    /**
     * @return array|ActionLink[]
     */
    public function getActionLinks()
    {
        $idContent = $this->idContent;
        /** @var \Cmf\Comment\Model\Repository\CommentRepository $repository */
        $repository = Application::getEntityManager()->getRepository($this->commentEntityName);
        //pre caching
        /** @var Comment[] $allComments */
        $allComments = $repository-> findBy(['content' => $idContent]);

        $mm = Application::getModuleManager();
        $moduleName = $mm->getModuleNameByClass($this->commentEntityName);
        $config = $this->getActionLinkConfig($idContent, $moduleName);

        $actionLinks = [];
        foreach ($allComments as $comment) {
            $idComment = $comment->getId();
            $idField = \Cmf\Component\Field\Factory::create([], $comment->getId());
            $actionLinks[$idComment] = \Cmf\Component\ActionLink\Factory::createLinks($config, $idField);
        }

        return $actionLinks;
    }

    /**
     * @return array|Comment[]
     */
    public function getComments()
    {
        $idContent = (int)$this->idContent;

        /** @var \Cmf\Comment\Model\Repository\CommentRepository $repository */
        $repository = Application::getEntityManager()->getRepository($this->commentEntityName);
        $comments = $repository->findBy(['content' => $idContent, 'level' => 1]);

        return $comments;
    }
}
