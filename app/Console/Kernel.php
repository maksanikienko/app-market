<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // REQUIRES: composer require spatie/laravel-backup (run on server, see DEPLOY.md)
        // Removes old backups at 01:00 per the retention policy in config/backup.php.
        $schedule->command('backup:clean')->dailyAt('01:00');
        // Backs up the database at 02:00 every day (server time).
        $schedule->command('backup:run --only-db')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
