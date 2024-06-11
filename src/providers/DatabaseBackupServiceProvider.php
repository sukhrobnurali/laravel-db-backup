<?php

namespace LaravelDbBackup\Providers;

use Illuminate\Support\ServiceProvider;

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
            // Publish the configuration file.
            $this->publishes([
                __DIR__.'/../../config/backup.php' => config_path('backup.php'),
            ], 'config');


        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge package configuration with the application's configuration.
        $this->mergeConfigFrom(
            __DIR__.'/../../config/backup.php',
            'backup'
        );
    }
}
