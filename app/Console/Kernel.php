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
        // Process recurring subscription billing daily
        $schedule->command('subscriptions:process-billing')->daily();
        
        // Check and expire subscriptions
        $schedule->call(function () {
            \App\Models\Subscription::where('status', 'active')
                ->where('end_date', '<', now())
                ->update(['status' => 'expired']);
        })->daily();
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
