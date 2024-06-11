<?php

namespace LaravelDbBackup\Commands;

use Illuminate\Console\Command;
use LaravelDbBackup\DatabaseBackupManager;
use LaravelDbBackup\Exceptions\BackupException;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database backup process';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $manager = new DatabaseBackupManager();

        try {
            $storedFiles = $manager->backupDatabase();
            $this->info("Backup completed successfully!");
            foreach ($storedFiles as $driverName => $path) {
                $this->line("Stored on [{$driverName}]: {$path}");
            }
            $manager->cleanupOldBackups();
            $this->info("Old backups cleaned up.");
        } catch (BackupException $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
