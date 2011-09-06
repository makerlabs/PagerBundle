<?php

/*
 * This file is part of the PagerBundle package.
 *
 * (c) Marcin Butlak <contact@maker-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MakerLabs\PagerBundle\Test;

use MakerLabs\PagerBundle\Pager;

/**
 * 
 * @author Marcin Butlak <contact@maker-labs.com>
 */
class PagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var \PHPUnit_Framework_MockObject_MockObject 
     */
    private $adapter;
    /**
     *
     * @var Pager
     */
    private $pager;

    protected function setUp()
    {
        $this->adapter = $this->getMock('MakerLabs\PagerBundle\Adapter\PagerAdapterInterface');

        $this->adapter->expects($this->any())
                ->method('getTotalResults')
                ->will($this->returnValue(100));

        $this->pager = new Pager($this->adapter);
    }

    public function testConstructor()
    {
        $pager = new Pager($this->adapter, array('page' => 2, 'limit' => 30));

        $this->assertEquals(2, $pager->getPage());

        $this->assertEquals(30, $pager->getLimit());
    }

    public function testNoResults()
    {
        $adapter = $this->getMock('MakerLabs\PagerBundle\Adapter\PagerAdapterInterface');

        $adapter->expects($this->any())
                ->method('getTotalResults')
                ->will($this->returnValue(0));

        $pager = new Pager($adapter);

        $this->assertEquals(1, $pager->getLastPage());

        $this->assertEquals(false, $pager->hasResults());
    }

    public function testDefaults()
    {
        $this->assertEquals(1, $this->pager->getPage());

        $this->assertEquals(20, $this->pager->getLimit());

        $this->assertEquals(1, $this->pager->getFirstPage());

        $this->assertEquals(true, $this->pager->isFirstPage());

        $this->assertEquals(5, $this->pager->getLastPage());

        $this->assertEquals(false, $this->pager->isLastPage());

        $this->assertEquals(2, $this->pager->getNextPage());

        $this->assertEquals(1, $this->pager->getPreviousPage());

        $this->assertEquals(true, $this->pager->isPaginable());
    }

    public function testIsLastPage()
    {
        $this->pager->setPage(5);

        $this->assertEquals(true, $this->pager->isLastPage());
    }

    public function testNotLastPage()
    {
        $this->pager->setPage(2);

        $this->assertEquals(false, $this->pager->isLastPage());
    }

    public function testNotFirstPage()
    {
        $this->pager->setPage(5);

        $this->assertEquals(false, $this->pager->isFirstPage());
    }

    public function testOutOfRangeLimit()
    {
        $this->pager->setPage(200);

        $this->assertEquals(5, $this->pager->getPage());

        $this->pager->setPage(-100);

        $this->assertEquals(1, $this->pager->getPage());

        $this->pager->setLimit(-100);

        $this->assertEquals(1, $this->pager->getLimit());
    }

    public function testResults()
    {
        $this->assertEquals(true, $this->pager->hasResults());

        $this->adapter->expects($this->any())
                ->method('getResults')
                ->with($this->equalTo(40), $this->equalTo(40));

        $this->pager->setPage(2)->setLimit(40);

        $this->pager->getResults();
    }

    public function testIsNotPaginable()
    {
        $this->pager->setLimit(100);

        $this->assertEquals(false, $this->pager->isPaginable());
    }
}
