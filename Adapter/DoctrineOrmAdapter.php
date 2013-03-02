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
use Doctrine\ORM\QueryBuilder;

/**
 * 
 * @author Marcin Butlak <contact@maker-labs.com>
 */
class DoctrineOrmAdapter implements PagerAdapterInterface
{
    protected $queryBuilder;
    protected $hydrationMode;
    protected $countQueryBuilder = null;
    protected $query = null;
    protected $count = null;

    public function __construct(QueryBuilder $qb, $hydration_mode = null)
    {
        $this->queryBuilder = $qb;

        $this->hydrationMode = $hydration_mode;
    }

    /**
     * Returns the count query instance
     * 
     * @return QueryBuilder
     */
    public function getCountQueryBuilder()
    {
        if (null === $this->countQueryBuilder || $this->queryBuilder->getState() == QueryBuilder::STATE_DIRTY) {
            $a = $this->queryBuilder->getRootAlias();

            $qb = clone $this->queryBuilder;

            if ($qb->getDQLPart('groupBy')) {
                $qb->resetDQLPart('groupBy')->select('COUNT(DISTINCT ' . $a . ')');
            } else {
                $qb->select('COUNT(' . $a . ')');   
            }

            $qb->resetDQLPart('orderBy')->setMaxResults(null)->setFirstResult(null);

            $this->countQueryBuilder = $qb;
        }

        return $this->countQueryBuilder;
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * Returns the total number of results
     * 
     * @return integer
     */
    public function count()
    {
        if (null === $this->count || $this->getCountQueryBuilder()->getState() == QueryBuilder::STATE_DIRTY) {
            $this->count = $this->getCountQueryBuilder()->getQuery()->getSingleScalarResult();
            $this->getQuery();
        }

        return $this->count;
    }

    /**
     * Returns the list of results 
     * 
     * @return array 
     */
    public function getResults($offset, $limit)
    {
        return $this->getQuery()->setFirstResult($offset)->setMaxResults($limit)->execute(array(), $this->hydrationMode);
    }

    protected function getQuery()
    {
        if (null === $this->query || $this->queryBuilder->getState() == QueryBuilder::STATE_DIRTY) {
            $this->query = $this->queryBuilder->getQuery();
        }

        return $this->query;
    }
}
