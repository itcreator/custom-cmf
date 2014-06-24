<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Data\Source;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class QueryBuilderSource extends AbstractSource
{
    /** @var \Doctrine\ORM\QueryBuilder */
    protected $qb;

    /** @var array */
    protected $data;

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     */
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (null === $this->data) {
            $this->data = $this->qb->getQuery()->getArrayResult();
        }

        return $this->data;
    }
}
