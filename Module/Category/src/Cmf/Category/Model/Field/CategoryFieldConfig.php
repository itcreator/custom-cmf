<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category\Model\Field;

use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Data\Filter\Trim;
use Cmf\Data\Source\QueryBuilderSource;
use Cmf\Data\Validator\StrLen;
use Cmf\System\Application;

/**
 * Fields config for categories
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CategoryFieldConfig extends AbstractFieldConfig
{
    /** @var string */
    protected $moduleName = 'Category';

    /** @var string */
    protected $controllerName = 'Category';

    /** @var string  */
    protected $entityName = 'Cmf\Category\Model\Entity\Category';

    /**
     * @return $this
     */
    protected function init()
    {
        $lng = \Cmf\Language\Factory::get($this);

        $em = Application::getEntityManager();
        $qb = $em->createQueryBuilder()
            ->select(['cat.id value', 'cat.title', 'cat.level'])
            ->from($this->entityName, 'cat')
            ->orderBy('cat.path, cat.title');

        $this->config = [
            'id' => [
                'display' => 'checkbox',
                'title' => 'id',
                'fieldType' => 'Id',
                'input' => 'Hidden',
                'sortable' => true
            ],
            'title' => [
                'decorator' => 'ItemLink',
                'urlParams' => [
                    'controller' => $this->controllerName,
                    'action' => 'read',
                    'module' => $this->moduleName
                ],
                'fields' => ['id' => 'id'],
                'sortable' => true,
                'required' => true,
                'title' => $lng['title'],
            ],
            'created' => [
                'title' => $lng['creationTime'],
                'decorator' => 'Time',
                'generable' => true,
            ],
            'updated' => [
                'title' => $lng['updatingTime'],
                'decorator' => 'Time',
                'generable' => true,
            ],
            'summary' => [
                'validators' => [new StrLen(0, 500)],
                'filters' => [new Trim()],
                'title' => $lng['summary'],
                'input' => 'TextArea',
                'hideInList' => true,
            ],
            'description' => [
                'validators' => [new StrLen(1, 20000)],
                'filters' => [new Trim()],
                'title' => $lng['description'],
                'input' => 'TextArea',
                'required' => true,
                'hideInList' => true,
            ],
            'parent' => [
                'input' => 'EntityTreeSelect',
                'entityName' => $this->entityName,
                'title' => $lng['parentCategory'],
                'dataSource' => new QueryBuilderSource($qb),
                'required' => true,
            ],
        ];
    }
}
