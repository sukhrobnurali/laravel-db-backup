<?php

namespace LaravelDbBackup\Exceptions;

use Exception;
use Throwable;

class BackupException extends Exception
{
    /**
     * Create a new BackupException instance.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     * @return void
     */
    public function __construct(string $message = 'Backup error occurred', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
