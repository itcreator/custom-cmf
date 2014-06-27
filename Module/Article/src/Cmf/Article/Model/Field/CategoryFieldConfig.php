<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Article\Model\Field;

/**
 * Fields config for article categories
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class CategoryFieldConfig extends \Cmf\Category\Model\Field\CategoryFieldConfig
{
    /** @var string */
    protected $moduleName = 'Cmf\Article';

    /** @var string */
    protected $controllerName = 'Category';

    /** @var string  */
    protected $entityName = 'Cmf\Article\Model\Entity\Category';
}
