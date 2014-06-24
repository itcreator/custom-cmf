<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2013 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Category;

use Cmf\System\Application;

/**
 * Category tree widget
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Tree
{
    /** @var string */
    protected $entityName;
    /** @var array */
    protected $urlParams;
    /** @var int|null */
    protected $idCategory;

    /**
     * @param string $entityName
     * @param array $urlParams
     * @param null|int $idCategory
     */
    public function __construct($entityName, array $urlParams = [], $idCategory = null)
    {
        $this->entityName = $entityName;
        $this->urlParams = $urlParams;
        $this->idCategory = $idCategory;
    }

    /**
     * @return \Cmf\Category\Model\Entity\Category[]
     */
    public function getCategory()
    {
        $em = Application::getEntityManager();

        return $em->find($this->entityName, $this->idCategory);
    }

    /**
     * @param int $idCategory
     * @return string
     */
    public function buildUrl($idCategory)
    {
        $params = array_merge($this->urlParams, ['id' => $idCategory]);

        return Application::getUrlBuilder()->build($params);
    }
}
