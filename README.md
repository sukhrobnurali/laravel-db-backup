# Laravel Database Backup

**Laravel Database Backup** is a robust, extensible package for Laravel that simplifies backing up your MySQL and PostgreSQL databases. Built with clean, object-oriented principles, this package offers scheduled backups, multi-storage support, and automatic cleanup of old backups.

## Features

- **Scheduled Backups:** Easily schedule backups daily, weekly, or monthly using Laravel's scheduler.
- **Multi-Storage Support:** Store backups locally—with the option to add remote storage drivers (e.g., S3, Google Drive) in the future.
- **Automatic Cleanup:** Automatically delete backups older than a specified retention period to save disk space.
- **Artisan Command:** Trigger backups manually using a simple Artisan command.
- **Custom Exception Handling:** Domain-specific error handling with custom exceptions.
- **Extensible Architecture:** Clean, modular design for easy integration and future enhancements.

## Installation

Install the package via Composer:

```bash
composer require sukhrobnurali/laravel-db-backup
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=config --provider="LaravelDbBackup\Providers\DatabaseBackupServiceProvider"
```

## Configuration

The configuration file (`config/backup.php`) allows you to customize several aspects of the package:

- **Local Storage:** Specify the directory where backups will be stored.
- **Remote Storage (Stub):** Configure remote storage options for future expansion.
- **Retention Policy:** Set the number of days to retain backups.
- **Scheduling:** Use cron expressions to schedule your backups.
- **Notifications (Stub):** Configure notification options (e.g., via Telegram) for backup events.

## Usage

To manually run a backup, simply execute the Artisan command:

```bash
php artisan backup:run
```

To schedule automatic backups, add the command to your Laravel scheduler in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('backup:run')->daily();
}
```

## Testing

This package includes a comprehensive test suite using [Orchestra Testbench](https://github.com/orchestral/testbench). To run the tests, execute:

```bash
vendor/bin/phpunit
```

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Write tests and ensure that everything passes.
4. Submit a pull request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

