<?php

namespace LaravelDbBackup;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use LaravelDbBackup\Drivers\LocalStorageDriver;
use LaravelDbBackup\Drivers\RemoteStorageDriver;
use LaravelDbBackup\Exceptions\BackupException;

class DatabaseBackupManager
{
    /**
     * Registered storage drivers.
     *
     * @var array
     */
    protected array $storageDrivers = [];


    public function __construct()
    {
        $this->initStorageDrivers();
    }

    /**
     * Initialize storage drivers based on configuration.
     *
     * @return void
     */
    protected function initStorageDrivers(): void
    {
        $config = Config::get('backup');

        $localPath = $config['local']['path'] ?? storage_path('backups');
        $this->storageDrivers['local'] = new LocalStorageDriver($localPath);

        if (!empty($config['remote']['enabled']) && $config['remote']['enabled'] === true) {
            $this->storageDrivers['remote'] = new RemoteStorageDriver($config['remote']);
        }
    }

    /**
     * Perform a database backup.
     *
     * @return array An associative array of storage driver names to stored file paths.
     * @throws BackupException
     */
    public function backupDatabase(): array
    {
        $databaseConnection = Config::get('database.default');
        $connection = DB::connection($databaseConnection);
        $databaseName = $connection->getDatabaseName();

        $timestamp    = Carbon::now()->format('Y_m_d_His');
        $dumpFileName = "{$databaseName}_{$timestamp}.sql";
        $dumpPath     = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $dumpFileName;

        $dbConfig = Config::get("database.connections.{$databaseConnection}");
        $driver   = $dbConfig['driver'];

        if ($driver === 'mysql') {
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['host']),
                escapeshellarg($databaseName),
                escapeshellarg($dumpPath)
            );
        } elseif ($driver === 'pgsql') {
            // For PostgreSQL, use the PGPASSWORD environment variable.
            $env     = "PGPASSWORD=" . escapeshellarg($dbConfig['password']) . " ";
            $command = $env . sprintf(
                'pg_dump --username=%s --host=%s %s > %s',
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['host']),
                escapeshellarg($databaseName),
                escapeshellarg($dumpPath)
            );
        } else {
            throw new BackupException("Unsupported database driver: {$driver}");
        }

        system($command, $exitCode);
        if ($exitCode !== 0) {
            throw new BackupException("Database dump failed with exit code {$exitCode}");
        }

        $storedFiles = [];
        foreach ($this->storageDrivers as $name => $driverInstance) {
            $storedFiles[$name] = $driverInstance->store($dumpPath);
        }

        unlink($dumpPath);

        return $storedFiles;
    }

    /**
     * Clean up backups older than the retention period.
     *
     * @return void
     */
    public function cleanupOldBackups(): void
    {
        $config        = Config::get('backup');
        $retentionDays = $config['retention_days'] ?? 30;
        $cutoff        = Carbon::now()->subDays($retentionDays)->timestamp;

        if (isset($this->storageDrivers['local'])) {
            $localDriver = $this->storageDrivers['local'];
            $backupPath  = $localDriver->getPath();
            foreach (glob($backupPath . '/*.sql') as $file) {
                if (filemtime($file) < $cutoff) {
                    $localDriver->delete($file);
                }
            }
        }
    }
}
