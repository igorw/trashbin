<?php

namespace Igorw\Trashbin;

class RedisStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $redis = $this->getMock('Predis\Client');

        $storage = new RedisStorage($redis);
        $storage->get('foo');
    }

    public function testSet()
    {
        $redis = $this->getMock('Predis\Client');

        $storage = new RedisStorage($redis);
        $storage->set('foo', array('a' => 'b'));
    }
}
