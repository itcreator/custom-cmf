<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Block\Model\Repository;

use Cmf\System\Application;
use Doctrine\ORM\EntityRepository;

/**
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class BindingRepository extends EntityRepository
{
    /**
     * @param string $containerName
     * @return array
     */
    public function getBlocksByContainerName($containerName)
    {
        $app = Application::getInstance();
        $mvcRequest = $app->getMvcRequest();

        $qb = $this->createQueryBuilder('binding')
            ->select(['binding', 'cont', 'bl'])
            ->Join('binding.container', 'cont')
            ->Join('binding.block', 'bl')
            ->where('cont.name=:containerName')
            ->andWhere('binding.controllerName=:controllerName')
            ->andWhere('binding.moduleName=:moduleName')
            ->setParameters([
                'containerName' => $containerName,
                'controllerName' => strtolower($mvcRequest->getControllerName()),
                'moduleName' => strtolower($mvcRequest->getModuleName()),
            ]);

        return $qb->getQuery()->getResult();
    }
}
