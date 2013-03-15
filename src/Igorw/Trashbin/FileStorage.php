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
            return null;
        }

        $json = file_get_contents($this->pathTo($id));

        return json_decode($json, true);
    }

    public function set($id, array $value)
    {
        $json = json_encode($value);

        return false !== file_put_contents($this->pathTo($id), $json);
    }

    private function pathTo($id)
    {
        return $this->storageDirectory.$id.'.json';
    }
}
