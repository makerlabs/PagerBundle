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
    * @var int
    */
   protected $page = 1;
   
   /**
    *
    * @var int
    */
   protected $limit;

   /**
    * Constructor
    * 
    * @param PagerAdapterInterface $adapter The pager adapter
    * @param array $options Additional options
    */
   public function __construct(PagerAdapterInterface $adapter, array $options = array())
   {
      $this->adapter = $adapter;

      $this->setLimit(isset($options['limit']) ? $options['limit'] : 20);

      if (isset($options['page']))
      {
         $this->setPage($options['page']);
      }
   }

   /**
    * Sets the current page number
    * 
    * @param int $page The current page number
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
    * @return int 
    */
   public function getPage()
   {
      return $this->page;
   }

   /**
    * Sets the results limit for one page
    * 
    * @param int $limit
    * @return Pager instance
    */
   public function setLimit($limit)
   {
      $this->limit = min($limit > 0 ? $limit : 1, $this->adapter->getTotalResults());

      return $this;
   }

   /**
    * Returns the current results limit for one page
    *  
    * @return int 
    */
   public function getLimit()
   {
      return $this->limit;
   }

   /**
    * Returns the next page number
    * 
    * @return int 
    */
   public function getNextPage()
   {
      return $this->page < $this->getLastPage() ? $this->page + 1 : $this->getLastPage();
   }

   /**
    * Returns the previous page number
    * 
    * @return int 
    */
   public function getPreviousPage()
   {
      return $this->page > $this->getFirstPage() ? $this->page - 1 : $this->getFirstPage();
   }

   /**
    * Returns the first page number
    * 
    * @return int 
    */
   public function getFirstPage()
   {
      return 1;
   }

   /**
    * Returns the last page number
    * 
    * @return int 
    */
   public function getLastPage()
   {
      return floor($this->adapter->getTotalResults() / $this->limit);
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
    * Returns true if the current page is last
    * 
    * @return boolean
    */
   public function isLastPage()
   {
      return $this->page == $this->getLastPage();
   }

   /**
    * Returns true if the current resultset requires pagination
    * 
    * @return boolean 
    */
   public function isPaginable()
   {
      return $this->adapter->getTotalResults() > $this->limit;
   }

   /**
    * Returns the current adapter instance
    * 
    * @return mixed 
    */
   public function getAdapter()
   {
      return $this->adapter;
   }

   /**
    * Generates a page list 
    * 
    * @param int $pages Number of pages to generate
    * @return array The page list 
    */
   public function getPages($pages = 10)
   {
      $tmp = $this->page - floor($pages / 2);

      $begin = $tmp > $this->getFirstPage() ? $tmp : $this->getFirstPage();

      $end = min($begin + $pages - 1, $this->getLastPage());

      return range($begin, $end, 1);
   }

   /**
    *
    * Returns results list for the current page and limit
    * 
    * @return array 
    */
   public function getResults()
   {
      return $this->adapter->getResults(($this->page - 1) * $this->limit, $this->limit);
   }
}
