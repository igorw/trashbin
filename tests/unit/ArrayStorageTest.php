<?php

namespace Igorw\Trashbin;

class ArrayStorageTest extends \PHPUnit_Framework_TestCase
{
    private $storage;

    public function setUp()
    {
        $this->storage = new ArrayStorage();
    }

    public function testGet()
    {
        $this->storage = new ArrayStorage(array('foo' => array('a' => 'b')));
        $this->assertSame(array('a' => 'b'), $this->storage->get('foo'));
    }

    public function testGetOfNonExistentValue()
    {
        $this->assertSame(null, $this->storage->get('foo'));
    }

    public function testSet()
    {
        $this->storage->set('foo', array('a' => 'b'));

        $this->assertSame(array('a' => 'b'), $this->storage->get('foo'));
    }

    public function testSetTwiceShouldOverrideValue()
    {
        $this->storage->set('foo', array('a' => 'b'));
        $this->storage->set('foo', array('c' => 'd'));

        $this->assertSame(array('c' => 'd'), $this->storage->get('foo'));
    }
}
