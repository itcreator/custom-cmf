<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Validator;

use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class NotInDb extends AbstractValidator
{
    const INVALID = 'invalid';

    /** @var string */
    protected $entity;

    /** @var string */
    protected $field;

    /**
     * @param string $entity
     * @param string $field
     */
    public function __construct($entity, $field)
    {
        $this->entity = $entity;
        $this->field = $field;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        $em = Application::getEntityManager();
        $result = $em->getRepository($this->entity)->findOneBy([$this->field => $value]);

        if ($result) {
            $lng = \Cmf\Language\Factory::get($this);
            $this->messages[self::INVALID] = $lng[self::INVALID];

            $res = false;
        } else {
            $res = true;
        }

        return $res;
    }
}
