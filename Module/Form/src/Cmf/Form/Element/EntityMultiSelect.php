<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form\Element;

use Cmf\Db\BaseEntity;
use Cmf\System\Application;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Multi-select for entities with checkboxes
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class EntityMultiSelect extends MultiSelect
{
    /** @var string */
    protected $entityName;

    /**
     * @param array $params
     * @throws \Cmf\Form\Exception
     */
    public function __construct($params = [])
    {
        $defaultParams = ['entityName' => null];

        $params = array_merge($this->defaultParams, $defaultParams, $params);

        parent::__construct($params);

        if ($params['entityName']) {
            $this->entityName = $params['entityName'];
        } else {
            throw new \Cmf\Form\Exception('Not found required parameter "entityName"');
        }
    }

    /**
     * @param \Doctrine\Common\Collections\Collection|BaseEntity[] $values
     *
     * @return $this
     */
    public function setValue($values = [])
    {
        $this->initializeElements();

        foreach ($this->getElements() as $element) {
            $element->setValue(false);
        }

        if ($values instanceof Collection) {
            foreach ($values as $value) {
                $id = $value->getId();
                $this->getElement($id)->setValue(true);
            }
        } else {
            foreach ((array)$values as $key => $value) {
                $this->getElement($key)->setValue(true);
            }
        }

        return $this;
    }

    /**
     * @return array|\Doctrine\Common\Collections\ArrayCollection|mixed|null|BaseEntity[]
     */
    public function getValue()
    {
        $values = [];
        foreach ($this->getElements() as $key => $element) {
            if ($element->getValue()) {
                $values[] = $key;
            }
        }

        $em = Application::getEntityManager();
        if ($values) {
            $qb = $em->getRepository($this->entityName)->createQueryBuilder('e');
            $qb->where($qb->expr()->in('e.id', $values));
            $values = $qb->getQuery()->getResult();
            $values = new ArrayCollection($values);
        } else {
            $values = null;
        }

        return $values;
    }
}
