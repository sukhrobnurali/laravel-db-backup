<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Local Storage Configuration
    |--------------------------------------------------------------------------
    | This is the path where your backups will be stored locally. It should
    | be outside your Laravel project folder if needed.
    */
    'local' => [
        'path' => env('DB_BACKUP_LOCAL_PATH', base_path('../db_backups')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Remote Storage Configuration (Stub)
    |--------------------------------------------------------------------------
    | Future drivers like S3, Google Drive, etc., can be configured here.
    */
    'remote' => [
        'enabled' => env('DB_BACKUP_REMOTE_ENABLED', false),
        // Additional remote storage configuration (e.g., credentials, endpoints) goes here.
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Retention Policy
    |--------------------------------------------------------------------------
    | Number of days to keep backups. Older backups will be deleted automatically.
    */
    'retention_days' => env('DB_BACKUP_RETENTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Scheduling Guidelines
    |--------------------------------------------------------------------------
    | Although the package provides an Artisan command, you can use these cron
    | expressions in your app's scheduler for daily, weekly, or monthly backups.
    */
    'schedules' => [
        'daily'   => '0 2 * * *',  // At 2 AM daily
        'weekly'  => '0 3 * * 0',  // At 3 AM every Sunday
        'monthly' => '0 4 1 * *',  // At 4 AM on the first day of every month
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings (Stub)
    |--------------------------------------------------------------------------
    | You can enable notifications (e.g., via Telegram) on backup success or
    | failure. This is planned for future implementation.
    */
    'notifications' => [
        'telegram' => [
            'enabled'   => env('DB_BACKUP_TELEGRAM_ENABLED', false),
            'bot_token' => env('DB_BACKUP_TELEGRAM_BOT_TOKEN', ''),
            'chat_id'   => env('DB_BACKUP_TELEGRAM_CHAT_ID', ''),
        ],
    ],

];
