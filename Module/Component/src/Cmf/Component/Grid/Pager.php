<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */

namespace Cmf\Component\Grid;

use Cmf\Component\Grid\Pager\Adapter\AbstractPagerAdapter;
use Doctrine\ORM\EntityRepository;
use Cmf\System\Application;

/**
 * Pager
 *
 * @author Vital Leshchyk <vitalleshchyk@gmail.com>
 */
class Pager implements \IteratorAggregate, \Countable
{
    /** @var AbstractPagerAdapter */
    protected $adapter = null;

    /**
     * Current page items
     * @var \Traversable
     */
    protected $items = null;

    /** @var int */
    protected $pageNumber = 1;

    /** @var int */
    protected $pagesCount = null;

    /** @var int */
    protected $itemsCountPerPage = 10;

    /** @var string */
    protected $pagerName = 'page';

    /** @var int */
    protected $totalCount = null;

    /** @var \Cmf\System\Sort */
    protected $sort;

    /**
     * @param AbstractPagerAdapter $adapter
     * @param array $params
     */
    public function __construct(AbstractPagerAdapter $adapter, array $params = [])
    {
        $this->adapter = $adapter;

        if (isset($params['itemsCountPerPage'])) {
            $this->itemsCountPerPage = $params['itemsCountPerPage'];
        }
        if (isset($params['pagerName'])) {
            $this->pagerName = $params['pagerName'];
        }

        if (($pageNumber = (int)Application::getRequest()->get($this->pagerName)) > 0) {
            $this->pageNumber = $pageNumber;
        }

        $this->sort = isset($params['sort']) ? $params['sort'] : [];
    }

    /**
     * @param mixed $data
     * @param array $params
     * @return \Cmf\Component\Grid\Pager
     * @throws Pager\Exception
     */
    public static function factory($data, $params = [])
    {
        if (is_array($data)) {
            return new self(new Pager\Adapter\Traversable($data), $params);
        } elseif ($data instanceof EntityRepository) {
            return new self(new Pager\Adapter\Doctrine($data), $params);
        }

        throw new Pager\Exception('Pager adapter not found.');
    }

    /**
     * @return int
     */
    protected function getOffset()
    {
        return ($this->pageNumber - 1) * $this->itemsCountPerPage;
    }

    /**
     * Method load items for current page
     *
     * @return void
     */
    protected function loadItemsForCurrentPage()
    {
        $items = $this->adapter->getItems($this->getOffset(), $this->itemsCountPerPage, $this->sort);
        if (!($items instanceof \Traversable)) {
            $this->items = new \ArrayIterator($items);
        } else {
            $this->items = $items;
        }
    }

    /**
     * @return \Traversable
     */
    public function getItems()
    {
        if (null === $this->items) {
            $this->loadItemsForCurrentPage();
        }

        return $this->items;
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->getItems();
    }

    /**
     * @return int
     */
    public function count()
    {
        $items = $this->getItems();

        return count($items);
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        if (null === $this->totalCount) {
            $this->totalCount = $this->adapter->getTotalCount();
        }

        return $this->totalCount;
    }

    /**
     * @return int|null
     */
    public function getPagesCount()
    {
        if (null === $this->pagesCount) {
            $pagesCount = ($this->getTotalCount() + $this->itemsCountPerPage - 1) / $this->itemsCountPerPage;
            $this->pagesCount = floor($pagesCount);
        }

        return $this->pagesCount;
    }

    /**
     * @param int $pageNumber
     * @return string
     */
    protected function getPageHref($pageNumber)
    {
        $vars = Application::getRequest()->getVars();
        if (1 == $pageNumber && isset($vars[$this->pagerName])) {
            unset($vars[$this->pagerName]);
        } elseif (1 < $pageNumber) {
            $vars[$this->pagerName] = $pageNumber;
        }

        return Application::getUrlBuilder()->build($vars);
    }

    /**
     * @return array
     */
    public function getPagerData()
    {
        $data = array();
        $pagesCount = $this->getPagesCount();

        if ($this->pageNumber > 1) {
            $data['prevPageHref'] = $this->getPageHref($this->pageNumber - 1);
            $data['firstPageHref'] = $this->getPageHref(1);
        }
        if ($this->pageNumber < $pagesCount) {
            $data['nextPageHref'] = $this->getPageHref($this->pageNumber + 1);
            $data['lastPageHref'] = $this->getPageHref($pagesCount);
        }

        $pagesArray = [];

        if ($this->pageNumber - 3 > 1) {
            $pageNode = [];
            $pageNode['href'] = $this->getPageHref(1);
            $pageNode['text'] = 1;
            $pageNode['type'] = 'link';
            $pagesArray[] = $pageNode;
        }
        if ($this->pageNumber - 4 > 1) {
            $pageNode = [];
            $pageNode['type'] = 'separator';
            $pagesArray[] = $pageNode;
        }

        for ($i = $this->pageNumber - 3; $i <= $this->pageNumber + 3; $i++) {
            if ($i < 1) {
                continue;
            }
            if ($i > $pagesCount) {
                continue;
            }

            $pageNode = [];
            $pageNode['href'] = $this->getPageHref($i);
            $pageNode['text'] = $i;

            if ($this->pageNumber == $i) {
                $pageNode['type'] = 'current';
            } else {
                $pageNode['type'] = 'link';
            }

            $pagesArray[] = $pageNode;
        }

        if ($this->pageNumber + 4 < $pagesCount) {
            $pageNode = [];
            $pageNode['type'] = 'separator';
            $pagesArray[] = $pageNode;
        }

        if ($this->pageNumber + 3 < $pagesCount) {
            $pageNode = [];
            $pageNode['href'] = $this->getPageHref($pagesCount);
            $pageNode['text'] = $pagesCount;
            $pageNode['type'] = 'link';
            $pagesArray[] = $pageNode;
        }

        $data['pagesArray'] = $pagesArray;
        $data['pagesCount'] = $pagesCount;

        return $data;
    }

    /**
     * @return int
     */
    public function getItemsCountPerPage()
    {
        return $this->itemsCountPerPage;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }
}
