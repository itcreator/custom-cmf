<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid\Pager\Adapter;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Doctrine extends AbstractPagerAdapter
{
    /** @var string */
    protected $entityAlias = '___pager_entity';

    /**
     * @param \Doctrine\ORM\EntityRepository $data
     */
    public function __construct($data)
    {
        $this->rawData = $data->createQueryBuilder($this->entityAlias);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param \Cmf\System\Sort $sort
     * @return mixed
     */
    public function getItems($offset, $limit, $sort = null)
    {
        static $items = null;
        if (null !== $items) {
            return $items;
        }
        if ($sort && $sort->getField()) {
            $this->rawData->addOrderBy($this->entityAlias . '.' . $sort->getField(), $sort->getDirection());
        }

        $items = $this->rawData->setMaxResults($limit)->setFirstResult($offset)->getQuery()->execute();
        return $items;
    }

    /**
     * @return int|null
     */
    public function getTotalCount()
    {
        $expr = new \Doctrine\ORM\Query\Expr();
        $result = $this->rawData
            ->setMaxResults(null)
            ->setFirstResult(null)
            ->select($expr->count($this->entityAlias . '.id') . ' AS totalCount')
            ->getQuery()
            ->getSingleResult();

        $result = $result ? $result['totalCount'] : null;

        return $result;
    }
}
