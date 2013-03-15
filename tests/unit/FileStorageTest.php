<?php

namespace Igorw\Trashbin;

class FileStorageTest extends \PHPUnit_Framework_TestCase
{
    private $tempDir;
    private $storage;

    public function setUp()
    {
        $this->tempDir = $this->createTempDir();
        $this->storage = new FileStorage($this->tempDir);
    }

    public function testGet()
    {
        copy(__DIR__.'/Fixtures/FileStorage/foo.json', $this->tempDir.'/foo.json');

        $this->assertSame(array('a' => 'b'), $this->storage->get('foo'));
    }

    public function testGetOfNonExistentValue()
    {
        $this->assertSame(null, $this->storage->get('foo'));
    }

    public function testSet()
    {
        $this->storage->set('foo', array('a' => 'b'));

        $this->assertJsonStringEqualsJsonString('{"a":"b"}', file_get_contents($this->tempDir.'/foo.json'));
    }

    public function testSetTwiceShouldOverrideValue()
    {
        $this->storage->set('foo', array('a' => 'b'));
        $this->storage->set('foo', array('c' => 'd'));

        $this->assertJsonStringEqualsJsonString('{"c":"d"}', file_get_contents($this->tempDir.'/foo.json'));
    }

    private function createTempDir()
    {
        $tempDir = tempnam(sys_get_temp_dir(), '');
        unlink($tempDir);
        mkdir($tempDir, 755, true);

        return $tempDir;
    }
}
