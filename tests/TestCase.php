<?php

namespace LaravelDbBackup\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use LaravelDbBackup\Providers\DatabaseBackupServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            DatabaseBackupServiceProvider::class,
        ];
    }
}
