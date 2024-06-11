<?php

namespace LaravelDbBackup\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelDbBackup\Commands\BackupCommand;

class DatabaseBackupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/backup.php' => config_path('backup.php'),
            ], 'config');

            $this->commands([
                BackupCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/backup.php',
            'backup'
        );
    }
}
