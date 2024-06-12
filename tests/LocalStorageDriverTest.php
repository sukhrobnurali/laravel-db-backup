<?php

namespace LaravelDbBackup\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use LaravelDbBackup\Drivers\LocalStorageDriver;
use LaravelDbBackup\Exceptions\BackupException;

class LocalStorageDriverTest extends TestCase
{
    protected string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'laravel_backup_test_' . uniqid();
        mkdir($this->tempDir, 0755, true);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempDir)) {
            foreach (glob($this->tempDir . '/*') as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            rmdir($this->tempDir);
        }
        parent::tearDown();
    }

    /**
     * @throws Exception
     */
    public function testStoreCopiesFile()
    {
        $sourceFile = $this->tempDir . DIRECTORY_SEPARATOR . 'dummy.sql';
        file_put_contents($sourceFile, 'Test backup content');

        $driver = new LocalStorageDriver($this->tempDir);
        $storedPath = $driver->store($sourceFile);

        $this->assertFileExists($storedPath);
        $this->assertEquals('Test backup content', file_get_contents($storedPath));
    }

    public function testDeleteRemovesFile()
    {
        $filePath = $this->tempDir . DIRECTORY_SEPARATOR . 'dummy.sql';
        file_put_contents($filePath, 'Test content');
        $this->assertFileExists($filePath);

        $driver = new LocalStorageDriver($this->tempDir);
        $driver->delete($filePath);

        $this->assertFileDoesNotExist($filePath);
    }

    public function testGetPathReturnsCorrectPath()
    {
        $driver = new LocalStorageDriver($this->tempDir);
        $this->assertEquals($this->tempDir, $driver->getPath());
    }

    /**
     * @throws Exception
     */
    public function testStoreThrowsExceptionWhenCopyFails()
    {
        $driver = new LocalStorageDriver($this->tempDir);
        $this->expectException(BackupException::class);
        $driver->store('/nonexistent/file.sql');
    }
}
