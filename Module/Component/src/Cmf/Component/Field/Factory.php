<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2012 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */


namespace Cmf\Component\Field;

use Cmf\Db\BaseEntity;
use Cmf\Structure\Collection\LazyAssociateCollection;
use Cmf\System\Application;

/**
 * Fields factory
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    /**
     * @param array $config
     * @param mixed $value
     * @return AbstractField
     */
    public static function create(array $config, $value)
    {
        $config = array_merge(['fieldType' => 'String', 'title' => null, 'decorator' => null], $config);

        $className = '\Cmf\Component\Field\\' . $config['fieldType'];
        return new $className($config, $value);
    }

    /**
     * @param string $moduleName
     * @param string $configKey
     * @return AbstractFieldConfig
     */
    public static function getConfig($moduleName, $configKey = 'field')
    {
        $config = Application::getConfigManager()->loadForModule($moduleName, $configKey);
        $className = $config->configClass;

        return new $className();
    }

    /**
     * @param int $id
     * @param string $entityName
     * @param AbstractFieldConfig $fieldsConfig
     * @return LazyAssociateCollection
     */
    public static function getFieldsById($id, $entityName, AbstractFieldConfig $fieldsConfig)
    {
        $em = Application::getEntityManager();
        /** @var $entity BaseEntity */
        if (false == $entity = $em->find($entityName, $id)) {
            return false;
        }

        $initFields = function (LazyAssociateCollection $fields) use ($fieldsConfig, $entity) {
            foreach ($fieldsConfig->getConfig() as $fieldName => $fieldConfig) {
                $getter = 'get' . $fieldName;
                $field = \Cmf\Component\Field\Factory::create($fieldConfig, $entity->$getter());
                $field->setEntity($entity);
                $fields->setItem($field, $fieldName);
            }
        };

        $fields = new LazyAssociateCollection($initFields);

        return $fields;
    }
}
