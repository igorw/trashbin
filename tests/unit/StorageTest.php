<?php

namespace Igorw\Trashbin;

class StorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $redis = $this->getMock('Predis\Client');

        $storage = new Storage($redis);
        $storage->get('foo');
    }

    public function testSet()
    {
        $redis = $this->getMock('Predis\Client');

        $storage = new Storage($redis);
        $storage->set('foo', array('a' => 'b'));
    }
}
