<?php

namespace Igorw\Trashbin;

class ArrayStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $storage = new ArrayStorage(array('foo' => array('a' => 'b')));
        $this->assertSame(array('a' => 'b'), $storage->get('foo'));
    }

    public function testSet()
    {
        $storage = new ArrayStorage();
        $storage->set('foo', array('a' => 'b'));

        $this->assertSame(array('a' => 'b'), $storage->get('foo'));
    }
}
