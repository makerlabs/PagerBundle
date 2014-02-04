<?php

/*
 * This file is part of the PagerBundle package.
 *
 * (c) Marcin Butlak <contact@maker-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MakerLabs\Bundle\PagerBundle\Adapter;

/**
 * Pager adapter interface
 * 
 * @author Marcin Butlak <contact@maker-labs.com>
 */
interface PagerAdapterInterface extends \Countable
{
    /**
     * Returns the list of results 
     * 
     * @return array 
     */
    function getResults($offset, $limit);
}
