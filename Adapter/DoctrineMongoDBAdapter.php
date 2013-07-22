<?php
/*
 * This file is part of the PagerBundle package.
 *
 * (c) Marcin Butlak <contact@maker-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MakerLabs\PagerBundle\Adapter;

use MakerLabs\PagerBundle\Adapter\PagerAdapterInterface;
use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * Pager Doctrine MongoDB adapter
 * 
 * @author Phil A. <github@smurfy.de>
 */
class DoctrineMongoDBAdapter implements PagerAdapterInterface
{
    protected $query;
    protected $totalResults = null;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Returns the count query instance
     * 
     * @return QueryBuilder
     */
    public function getCountQuery()
    {
        $countQuery = clone $this->query->getQuery();
        return $countQuery;
    }

    /**
     * Returns the total number of results
     * 
     * @return integer
     */
    public function getTotalResults()
    {
        if (null === $this->totalResults) {
            $this->totalResults = $this->getCountQuery()->count();
        }
        return $this->totalResults;
    }

    /**
     * Returns the list of results 
     * 
     * @return array 
     */
    public function getResults($offset, $limit)
    {
        return $this->query->limit($limit)->skip($offset)->getQuery()->execute();
    }
}