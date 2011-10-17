<?php

/*
 * This file is part of the PagerBundle package.
 *
 * (c) Marcin Butlak <contact@maker-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MakerLabs\PagerBundle;

use MakerLabs\PagerBundle\Adapter\PagerAdapterInterface;

/**
 * Pager 
 * 
 * @author Marcin Butlak <contact@maker-labs.com>
 */
class Pager
{
    /**
     *
     * @var integer
     */
    protected $page = 1;

    /**
     *
     * @var integer
     */
    protected $limit = 20;
    
     /**
      * 
      * @var integer
      */
     protected $maxPages = 10;
     
    /**
     * Constructor
     * 
     * @param PagerAdapterInterface $adapter The pager adapter
     * @param array $options Additional options
     */
    public function __construct(PagerAdapterInterface $adapter, array $options = array())
    {
        $this->adapter = $adapter;

        if (isset($options['limit'])) {
            $this->setLimit($options['limit']);
        }

        if (isset($options['page'])) {
            $this->setPage($options['page']);
        }
        
        if (isset($options['max_pages'])) {
            $this->setMaxPages($options['max_pages']);
        }
    }

    /**
     * Sets the current page number
     * 
     * @param integer $page The current page number
     * @return Pager instance
     */
    public function setPage($page)
    {
        $this->page = min($page > 0 ? $page : $this->getFirstPage(), $this->getLastPage());

        return $this;
    }

    /**
     * Returns the current page number
     * 
     * @return integer 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Sets the results limit for one page
     * 
     * @param integer $limit
     * @return Pager instance
     */
    public function setLimit($limit)
    {
        $this->limit = $limit > 0 ? $limit : 1;

        $this->setPage($this->page);

        return $this;
    }

    /**
     * Returns the current results limit for one page
     *  
     * @return integer 
     */
    public function getLimit()
    {
        return $this->limit;
    }
    
        
     /**
      * Sets the number of pages shown
      * 
      * @param integer
      * @return Pager instance
      */
     public function setMaxPages($maxPages)
     {
         $this->maxPages = $maxPages;
 
         return $this;
     }
 
     /**
      * Returns the number of pages shown
      * 
      * @return integer 
      */
     public function getMaxPages()
     {
         return $this->maxPages;
     }

    /**
     * Returns the next page number
     * 
     * @return integer 
     */
    public function getNextPage()
    {
        return $this->page < $this->getLastPage() ? $this->page + 1 : $this->getLastPage();
    }

    /**
     * Returns the previous page number
     * 
     * @return integer 
     */
    public function getPreviousPage()
    {
        return $this->page > $this->getFirstPage() ? $this->page - 1 : $this->getFirstPage();
    }

    /**
     * Returns true if the current page is first
     * 
     * @return boolean 
     */
    public function isFirstPage()
    {
        return $this->page == 1;
    }

    /**
     * Returns the first page number
     * 
     * @return integer 
     */
    public function getFirstPage()
    {
        return 1;
    }

    /**
     * Returns true if the current page is last
     * 
     * @return boolean
     */
    public function isLastPage()
    {
        return $this->page == $this->getLastPage();
    }

    /**
     * Returns the last page number
     * 
     * @return integer 
     */
    public function getLastPage()
    {
        return $this->hasResults() ? ceil($this->adapter->getTotalResults() / $this->limit) : $this->getFirstPage();
    }

    /**
     * Returns true if the current result set requires pagination
     * 
     * @return boolean 
     */
    public function isPaginable()
    {
        return $this->adapter->getTotalResults() > $this->limit;
    }

    /**
     * Generates a page list 
     * 
     * @param integer $pages Number of pages to generate
     * @return array The page list 
     */
    public function getPages()
    {
        $pages = $this->getMaxPages();
        
        $tmp = $this->page - floor($pages / 2);

        $begin = $tmp > $this->getFirstPage() ? $tmp : $this->getFirstPage();

        $end = min($begin + $pages - 1, $this->getLastPage());

        return range($begin, $end, 1);
    }

    /**
     * Returns true if the current result set is not empty
     * 
     * @return boolean 
     */
    public function hasResults()
    {
        return $this->adapter->getTotalResults() > 0;
    }

    /**
     *
     * Returns results list for the current page and limit
     * 
     * @return array 
     */
    public function getResults()
    {
        return $this->hasResults() ? $this->adapter->getResults(($this->page - 1) * $this->limit, $this->limit) : array();
    }

    /**
     * Returns the current adapter instance
     * 
     * @return PagerAdapterInterface 
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}