<?php

namespace LaravelDbBackup\Drivers;

use LaravelDbBackup\Contracts\BackupStorageInterface;

class RemoteStorageDriver implements BackupStorageInterface
{
    /**
     * Configuration for the remote driver.
     *
     * @var array
     */
    protected array $config;

    /**
     * RemoteStorageDriver constructor.
     *
     * @param array $config The remote storage configuration.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        // Initialize remote connection here if needed.
    }

    /**
     * {@inheritdoc}
     */
    public function store(string $filePath, ?string $destination = null): string
    {
        // Stub: Implement remote storage logic.
        // For now, simulate storage by returning a dummy remote path.
        return "remote://" . basename($filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $filePath): void
    {
        // Stub: Implement remote file deletion.
    }
}
