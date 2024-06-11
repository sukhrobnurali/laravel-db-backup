<?php

namespace LaravelDbBackup\Drivers;

use Exception;
use LaravelDbBackup\Contracts\BackupStorageInterface;
use LaravelDbBackup\Exceptions\BackupException;

class LocalStorageDriver implements BackupStorageInterface
{
    /**
     * @var string
     */
    protected string $path;

    /**
     * LocalStorageDriver constructor.
     *
     * @param string $path The directory where backups should be stored.
     */
    public function __construct(string $path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR);
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Return the local backup path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function store(string $filePath, ?string $destination = null): string
    {
        $destinationPath = $destination ?? $this->path . DIRECTORY_SEPARATOR . basename($filePath);
        if (!copy($filePath, $destinationPath)) {
            throw new BackupException("Failed to copy file to {$destinationPath}");
        }
        return $destinationPath;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
