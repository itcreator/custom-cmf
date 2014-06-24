<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Table\Rows;

use Cmf\Component\ActionLink\AbstractConfig;

/**
 * Rows adapter for arrays
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class ArrayAdapter extends A
{
    /**
     * @param mixed $data
     * @param int|null $idField
     * @param array $fields
     * @param AbstractConfig $actionLinksConfig
     */
    public function __construct($data, $idField = null, $fields = [], AbstractConfig $actionLinksConfig = null)
    {
        parent::__construct($data, $idField, $fields, $actionLinksConfig);

        foreach ($data as $item) {
            $this->rows[] = \Cmf\Component\Grid\Table\Row\Factory::create($item, $this, $idField, $actionLinksConfig);
        }
    }
}
