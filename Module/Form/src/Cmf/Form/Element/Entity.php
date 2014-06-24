<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Element;

use Cmf\Db\BaseEntity;
use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Entity extends Select
{
    /** @var string */
    protected $entityName;

    /**
     * Parameter entityName is required
     *
     * @param array $params
     * @throws \Cmf\Form\Exception
     */
    public function __construct($params = [])
    {
        $params = array_merge($this->defaultParams, ['entityName' => null], $params);

        parent::__construct($params);

        $this->attributes->type = 'select';

        if ($params['entityName']) {
            $this->entityName = $params['entityName'];
        } else {
            throw new \Cmf\Form\Exception('Not found required parameter "entityName"');
        }
    }

    /**
     * @return null|BaseEntity
     */
    public function getValue()
    {
        //TODO: refactor value field and getters
        if ($this->value instanceof BaseEntity) {
            return $this->value;
        }
        $em = Application::getEntityManager();

        return $this->value ? $em->find($this->entityName, $this->value) : null;
    }

    /**
     * @return mixed
     */
    public function getIntValue()
    {
        $result = $this->value instanceof BaseEntity ? $this->value->getId() : $this->value;

        return $result;
    }
}
