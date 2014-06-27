<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Controller;

/**
 * Comment Controller for articles
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CommentController extends \Cmf\Comment\Controller\CommentController
{
    /** @var string */
    protected $commentEntityName = 'Cmf\Article\Model\Entity\Comment';

    /** @var string */
    protected $contentEntityName = 'Cmf\Article\Model\Entity\Article';

    /** @var string  */
    protected $contentControllerName = 'Article';
}
