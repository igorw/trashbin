<?php

namespace Igorw\Trashbin;

class FileStorage implements Storage
{
    private $storageDirectory;

    public function __construct($storageDirectory)
    {
        $this->storageDirectory = rtrim($storageDirectory, '\\/').'/';
    }

    public function get($id)
    {
        if (!file_exists($this->pathTo($id))) {
            return false;
        }
        return json_decode(file_get_contents($this->pathTo($id)));
    }

    public function set($id, array $value)
    {
        return file_put_contents($this->pathTo($id), json_encode($value)) !== false;
    }

    private function pathTo($id)
    {
        return $this->storageDirectory.$id.'.json';
    }
}
