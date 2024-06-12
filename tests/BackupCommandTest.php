<?php

namespace LaravelDbBackup\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use LaravelDbBackup\DatabaseBackupManager;
use LaravelDbBackup\Commands\BackupCommand;

class FakeDatabaseBackupManager extends DatabaseBackupManager
{
    public function backupDatabase(): array
    {
        return ['local' => '/fake/path/dummy.sql'];
    }

    public function cleanupOldBackups(): void
    {
        // No operation for testing purposes.
    }
}

class BackupCommandTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app->bind(DatabaseBackupManager::class, function () {
            return new FakeDatabaseBackupManager();
        });
    }

    /**
     * @throws BindingResolutionException
     */
    public function testBackupCommandOutputsSuccessMessage()
    {
        $this->app->make('Illuminate\Contracts\Console\Kernel')->registerCommand(new BackupCommand());

        $this->artisan('backup:run')
             ->expectsOutput('Backup completed successfully!')
             ->expectsOutput('Stored on [local]: /fake/path/dummy.sql')
             ->expectsOutput('Old backups cleaned up.')
             ->assertExitCode(0);
    }
}
