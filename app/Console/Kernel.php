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
        // Cek peringatan + kirim email setiap hari jam 07:00
        $schedule->command('siakad:cek-peringatan')
                 ->dailyAt('07:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/peringatan.log'));
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
