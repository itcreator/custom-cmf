<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category\Validator;

use Cmf\Data\Validator\AbstractValidator;
use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class NotChild extends AbstractValidator
{
    const INVALID = 'invalid';
    /** @var int */
    protected $nodeId;
    /** @var string */
    protected $entityName;

    /**
     * @param int $nodeId
     * @param string $entityName
     */
    public function __construct($nodeId, $entityName)
    {
        $this->nodeId = (int)$nodeId;
        $this->entityName = $entityName;
    }

    /**
     * @param \Cmf\Category\Model\Entity\Category $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!$this->nodeId) {
            return true;
        }

        $em = Application::getEntityManager();
        $repository = $em->getRepository($this->entityName);
        $parentNode = $repository->find($this->nodeId);

        if (0 === strpos($value->getPath(), $parentNode->getPath())) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID] = $lng[self::INVALID];
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }
}
