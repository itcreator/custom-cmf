<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Block\Model\Entity;

use Cmf\Db\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 *
 * @ORM\Table(name="block_container")
 * @ORM\Entity
 */
class Container extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    protected $name;
}
