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

/**
 * Pager array adapter
 * 
 * @author Marcin Butlak <contact@maker-labs.com>
 */
class ArrayAdapter implements PagerAdapterInterface, \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var array
     */
    protected $array;

    /**
     * @var int
     */
    protected $cursor = 0;

    /**
     * @var int
     */
    protected $totalItems;

    public function __construct(array $array = null)
    {
        if(null !== $array) {
            $this->setArray($array);
        }
    }

    /**
     * {@inheritDoc}
     */
    function countResults($offset = null, $limit = null) {
        return count(array_slice($this->array, $offset, $limit));
    }

    public function getTotalResults()
    {
        if (null === $this->totalItems) {
            $this->totalItems = $this->countResults();
        }

        return $this->totalItems;
    }

    public function getResults($offset, $limit)
    {
        return array_slice($this->array, $offset, $limit);
    }

    public function isEmpty()
    {
        return empty($this->array);
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);

        $this->totalItems = null;
    }

    public function current()
    {
        return $this->offsetGet($this->cursor);
    }

    public function key()
    {
        return $this->cursor;
    }

    public function next()
    {
        $this->cursor++;
    }

    public function rewind()
    {
        $this->cursor = 0;
    }

    public function valid()
    {
        return $this->offsetExists($this->cursor);
    }

    public function count()
    {
        return $this->getTotalResults();
    }

    /**
     * Set the array
     * @param $array
     * @return ArrayAdapter Provides a fluent interface
     */
    public function setArray($array) {
        $this->array = $array;
        $this->totalItems = null;
        return $this;
    }

    /**
     * Get the array
     * @return array
     */
    public function getArray() {
        return $this->array;
    }
}