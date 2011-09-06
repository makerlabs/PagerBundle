<?php

namespace MakerLabs\PagerBundle\Test\Adapter;

use MakerLabs\PagerBundle\Adapter\ArrayAdapter;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var array Test array
     */
    private $array;
    /**
     *
     * @var ArrayAdapter
     */
    private $adapter;

    public function setUp()
    {
        $this->array = array('one', 'two', 'three');

        $this->adapter = new ArrayAdapter($this->array);
    }

    public function testOffset()
    {
        $this->assertEquals($this->array[0], $this->adapter[0]);
    }

    public function testCount()
    {
        $this->assertEquals(count($this->array), count($this->adapter));
    }

    public function testUnsetIsset()
    {
        unset($this->adapter[1]);

        $this->assertFalse(isset($this->adapter[1]));
    }

    public function testGetResults()
    {
        $this->assertEquals(array_slice($this->array, 1, 2), $this->adapter->getResults(1, 2));
    }
}