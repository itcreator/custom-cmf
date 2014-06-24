<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\Field;

use Cmf\Article\Model\Entity\Article;
use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Data\Filter\Trim;
use Cmf\Data\Source\ArraySource;
use Cmf\Data\Source\QueryBuilderSource;
use Cmf\Data\Validator\KeyExist;
use Cmf\Data\Validator\StrLen;
use Cmf\System\Application;

/**
 * Fields config for articles
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ArticleFieldConfig extends AbstractFieldConfig
{
    /**
     * @return $this
     */
    protected function init()
    {
        $lng = \Cmf\Language\Factory::get($this);

        $notModeratedStatus = ['value' => Article::STATUS_NOT_MODERATED, 'title' => $lng['status_not_moderated']];
        $articleStatuses = [
            Article::STATUS_NOT_MODERATED => $notModeratedStatus,
            Article::STATUS_ACTIVE => ['value' => Article::STATUS_ACTIVE, 'title' => $lng['status_active']],
            Article::STATUS_DISABLED =>['value' => Article::STATUS_DISABLED, 'title' => $lng['status_disabled']],
            Article::STATUS_DELETED => ['value' => Article::STATUS_DELETED, 'title' => $lng['status_deleted']],
        ];

        $categoryQb = Application::getEntityManager()->createQueryBuilder()
            ->select(['cat.id value', 'cat.title', 'cat.level'])
            ->from('Cmf\Article\Model\Entity\Category', 'cat')
            ->orderBy('cat.path, cat.title');

        $this->config = [
            'id' => ['display' => 'checkbox', 'title' => 'id', 'fieldType' => 'Id', 'input' => 'Hidden'],
            'title' => [
                'decorator' => 'ItemLink',
                'urlParams' => ['controller' => 'article', 'action' => 'read', 'module' => 'Cmf\Article'],
                'fields' => ['id' => 'id'],
                'sortable' => true,
                'required' => true,
                'title' => $lng['title'],
            ],
            'summary' => [
                'validators' => [new StrLen(0, 500)],
                'filters' => [new Trim()],
                'title' => $lng['summary'],
                'input' => 'TextArea',
                'hideInList' => true,
            ],
            'text' => [
                'validators' => [new StrLen(1, 20000)],
                'filters' => [new Trim()],
                'title' => $lng['text'],
                'input' => 'TextArea',
                'required' => true,
                'hideInList' => true,
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
            'status' => [
                'title' => $lng['status'],
                'decorator' => 'StatusString',
                'dataSource' => new ArraySource($articleStatuses),
                'input' => 'Select',
                'required' => true,
                'validators' => [new KeyExist($articleStatuses)],
                'filters' => [new Trim()],
            ],
            'user' => [
                'title' => $lng['author'],
                'fieldType' => 'Foreign',
                'foreign' => [
                    'fieldName' => 'userName',
                    'urlParams' => ['controller' => 'User', 'action' => 'read', 'module' => 'Cmf\User'],
                    'fields' => ['id' => 'id'],
                ],
                'decorator' => 'ItemLink',
                'fields' => ['id' => 'id'],
                'generable' => true,
            ],
            'categories' => [
                'title' => $lng['categories'],
                'dataSource' => new QueryBuilderSource($categoryQb),
                'entityName' => 'Cmf\Article\Model\Entity\Category',
                'fieldType' => 'ForeignCollection',
                'input' => 'EntityMultiSelect',
                'foreign' => [
                    'fieldName' => 'companyName',
                    'urlParams' => ['controller' => 'Category', 'action' => 'read', 'module' => 'Cmf\Article'],
                    'fields' => ['id' => 'id'],
                ],
                'decorator' => 'ItemLinkCollection',
            ],
        ];

        return $this;
    }
}
