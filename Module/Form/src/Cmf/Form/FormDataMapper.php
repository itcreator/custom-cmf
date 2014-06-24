<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Form;

use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Db\BaseEntity;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class FormDataMapper
{
    /**
     * @param AbstractFieldConfig $fieldConfig
     * @param BaseEntity $entity
     * @param Form $form
     * @return $this;
     */
    public function fillEntityFromForm(AbstractFieldConfig $fieldConfig, BaseEntity $entity, Form $form)
    {
        foreach ($fieldConfig->getConfig() as $fieldName => $params) {
            if ('id' == $fieldName) {
                continue;
            }
            $setter = 'set' . $fieldName;
            if ($element = $form->getElement($fieldName)) {
                $entity->$setter($element->getValue());
            }
        }

        return $this;
    }

    /**
     * @param AbstractFieldConfig $fieldConfig
     * @param BaseEntity $entity
     * @param Form $form
     * @return $this;
     */
    public function fillFormFromEntity(AbstractFieldConfig $fieldConfig, BaseEntity $entity, Form $form)
    {
        foreach ($fieldConfig->getConfig() as $fieldName => $params) {
            $getter = 'get' . $fieldName;
            if ($element = $form->getElement($fieldName)) {
                $element->setValue($entity->$getter());
            }
        }

        return $this;
    }
}
