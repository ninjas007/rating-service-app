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
        // $schedule->command('inspire')->hourly();

        $schedule->command('db:backup')
                ->dailyAt('01:00')
                ->appendOutputTo(storage_path('logs/scheduler.log'));

        // $schedule->command('hl7:multi-listen')
        //     ->everyMinute()
        //     ->appendOutputTo(storage_path('logs/hl7_multi_listen.log'));

        // $schedule->command('hl7:process')
        //     ->everyMinute()
        //     ->appendOutputTo(storage_path('logs/hl7_process.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
