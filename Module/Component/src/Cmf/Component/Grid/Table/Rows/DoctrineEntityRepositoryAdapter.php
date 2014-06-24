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
use Cmf\Component\Grid\Table\Row\Factory as RowFactory;
use Doctrine\ORM\EntityRepository;

/**
 * Rows adapter for doctrine repositories
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class DoctrineEntityRepositoryAdapter extends A
{
    /**
     * @param \Doctrine\ORM\EntityRepository $data
     * @param int|null $idField
     * @param array $fields
     * @param AbstractConfig $actionLinksConfig
     */
    public function __construct(EntityRepository $data, $idField = null, $fields = [], $actionLinksConfig = null)
    {
        parent::__construct($data, $idField, $fields);

        $this->idField = $idField;
        $data = $data->createQueryBuilder('a')->setMaxResults(5)->setFirstResult(0) // for paginator
            ->andWhere('a.id =1')->orWhere('a.id<>?1')->setParameters(array(1 => 4))->getQuery()->execute();

        $n = count($data);
        for ($i = 0; $i < $n; $i++) {
            $this->rows[] = RowFactory::create($data[$i], $this, $idField, $actionLinksConfig);
        }
    }
}
