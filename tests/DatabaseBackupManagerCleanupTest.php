<?php

namespace LaravelDbBackup\Tests;

use Carbon\Carbon;
use LaravelDbBackup\DatabaseBackupManager;

class DatabaseBackupManagerCleanupTest extends TestCase
{
    protected string $tempBackupDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempBackupDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'laravel_backup_manager_test_' . uniqid();
        mkdir($this->tempBackupDir, 0755, true);

        config()->set('backup.local.path', $this->tempBackupDir);
        config()->set('backup.retention_days', 1);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempBackupDir)) {
            foreach (glob($this->tempBackupDir . '/*.sql') as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            rmdir($this->tempBackupDir);
        }
        parent::tearDown();
    }

    public function testCleanupOldBackupsDeletesOldFiles()
    {
        $oldFile = $this->tempBackupDir . DIRECTORY_SEPARATOR . 'old_backup.sql';
        file_put_contents($oldFile, 'old backup');
        touch($oldFile, Carbon::now()->subDays(2)->timestamp);

        $newFile = $this->tempBackupDir . DIRECTORY_SEPARATOR . 'new_backup.sql';
        file_put_contents($newFile, 'new backup');
        touch($newFile, Carbon::now()->timestamp);

        $manager = new DatabaseBackupManager();
        $manager->cleanupOldBackups();

        $this->assertFileDoesNotExist($oldFile);
        $this->assertFileExists($newFile);
    }
}
