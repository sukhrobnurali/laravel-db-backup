<?php

namespace LaravelDbBackup\Contracts;

interface BackupStorageInterface
{
    /**
     * Store the backup file.
     *
     * @param string      $filePath    The full path of the backup file to store.
     * @param string|null $destination (Optional) A custom destination path.
     * @return string The final path where the backup was stored.
     */
    public function store(string $filePath, ?string $destination = null): string;

    /**
     * Delete a backup file.
     *
     * @param string $filePath The full path of the file to delete.
     * @return void
     */
    public function delete(string $filePath): void;
}
