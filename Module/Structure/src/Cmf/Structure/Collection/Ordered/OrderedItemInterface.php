<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2014 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Structure\Collection\Ordered;

/**
 * Interface for item from ordered collection
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
interface OrderedItemInterface
{
    /**
     * @param int $weight
     * @return OrderedItemInterface
     */
    public function setWeight($weight);

    /**
     * @return int
     */
    public function getWeight();
}
