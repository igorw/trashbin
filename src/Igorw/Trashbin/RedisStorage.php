<?php

namespace Igorw\Trashbin;

use Predis\Client;

class RedisStorage implements Storage
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function get($id)
    {
        return $this->redis->hgetall($id);
    }

    public function set($id, array $data)
    {
        return $this->redis->hmset($id, $data);
    }
}
