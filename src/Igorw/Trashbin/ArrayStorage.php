<?php

namespace Igorw\Trashbin;

class ArrayStorage implements Storage
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get($id)
    {
        return isset($this->data[$id]) ? $this->data[$id] : null;
    }

    public function set($id, array $value)
    {
        return $this->data[$id] = $value;
    }
}
