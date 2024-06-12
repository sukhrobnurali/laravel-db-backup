<?php

namespace LaravelDbBackup\Tests;

use PHPUnit\Framework\TestCase;
use LaravelDbBackup\Drivers\RemoteStorageDriver;

class RemoteStorageDriverTest extends TestCase
{
    public function testStoreReturnsRemotePath()
    {
        $config = ['dummy' => 'value'];
        $driver = new RemoteStorageDriver($config);

        $fakeFilePath = '/path/to/dummy.sql';
        $result = $driver->store($fakeFilePath);
        $this->assertStringStartsWith('remote://', $result);
    }

    public function testDeleteDoesNotThrowException()
    {
        $config = ['dummy' => 'value'];
        $driver = new RemoteStorageDriver($config);
        $driver->delete('/non/existent/file.sql');
        $this->addToAssertionCount(1);
    }
}
