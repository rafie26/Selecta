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
        // Auto checkout rooms periodically for occupied bookings on their checkout date (Asia/Jakarta)
        $schedule->command('rooms:auto-checkout')
            ->everyFiveMinutes()
            ->timezone('Asia/Jakarta');

        // Auto check-in rooms periodically for paid bookings on their check-in date (Asia/Jakarta)
        $schedule->command('rooms:auto-checkin')
            ->everyFiveMinutes()
            ->timezone('Asia/Jakarta');
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
