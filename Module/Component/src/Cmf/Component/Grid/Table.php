<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid;

use Cmf\Component\ActionLink\AbstractConfig;
use Cmf\Component\Field\AbstractFieldConfig;
use Cmf\Structure\Collection\LazyCollection;

/**
 * Table
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Table
{
    /** @var \Cmf\Component\Grid\Table\Rows\A */
    protected $rows;

    /** @var \Cmf\Component\Grid\Table\Header */
    protected $header = null;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $defaultParams = [
            'actionLinks' => null,
            'data' => null,
            'fields' => null,
        ];

        $params = array_merge($defaultParams, $params);

        /** @var AbstractConfig|null $actionLinksConfig */
        $actionLinksConfig = $params['actionLinks'];
        /** @var AbstractFieldConfig $fields */
        $fields = $params['fields'];
        $initHeader = function (LazyCollection $headerFields) use ($fields, $actionLinksConfig) {
            $headerFields->setItems($fields->getConfig(true));
            if ($actionLinksConfig instanceof AbstractConfig) {
                $linkConfig = $actionLinksConfig->getConfig();
                $headerFields->setItem(['title' => $linkConfig['title']]);
            }
        };
        $this->header = new Table\Header(new LazyCollection($initHeader));

        $idField = isset($params['idField']) ? $params['idField'] : [];

        $this->rows = Table\Rows\Factory::create($params['data'], $idField, $fields, $actionLinksConfig);
        $this->rows->setTable($this);
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->header->getFields();
    }

    /**
     * @return Table\Rows\A
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return Table\Header
     */
    public function getHeader()
    {
        return $this->header;
    }
}
