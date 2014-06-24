<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
namespace Cmf\Form;

use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Data\Validator\NotInDb;
use Cmf\System\Application;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Factory
{
    /**
     * @param string $entityName
     * @param AbstractFieldConfig $fieldsConfig
     * @param int | null $id primary key value
     * @return Form
     */
    public static function create($entityName, AbstractFieldConfig $fieldsConfig, $id = null)
    {
        $app = Application::getInstance();
        $mvcRequest = $app->getMvcRequest();

        $urlParams = [
            'module' => $mvcRequest->getModuleName(),
            'controller' => $mvcRequest->getControllerName(),
            'action' => $id ? 'edit' : 'create',
        ];
        
        if ($id) {
            $urlParams['id'] = $id;
        }
        $action = new \Cmf\Component\Html\Expression(Application::getUrlBuilder()->build($urlParams));

        $formParams = [
            'action' => $action,
            'method' => \Cmf\Form\Constants::HTTP_METHOD_POST,
        ];
        $form = new \Cmf\Form\Form($formParams);

        $defaultParams = [
            'generable' => false,
            'unique' => false,
        ];

        foreach ($fieldsConfig->getConfig() as $fieldName => $params) {
            $params = array_merge($defaultParams, $params);

            if ('id' == $fieldName && !$id) {
                continue;
            }
            if ($params['generable']) {
                continue;
            }
            $element = \Cmf\Form\Element\Factory::create($params);
            $element->getAttributes()->setItem($fieldName, 'name');
            $form->setElement($element);
            if ('id' == $fieldName) {
                $element->setValue($id);
            }
            if (!$id && $params['unique']) {
                $element->addValidator(new NotInDb($entityName, $fieldName));
            }
        }

        return $form;
    }
}
