<?php

namespace LaravelDbBackup\Notifications;

class TelegramNotifier
{
    /**
     * Send a notification to a Telegram bot.
     *
     * @param string $message
     * @return void
     */
    public function notify(string $message): void
    {
        // Future implementation: Send $message to the configured Telegram bot.
    }
}
