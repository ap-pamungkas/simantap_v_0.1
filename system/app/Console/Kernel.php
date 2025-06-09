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
        // Jalankan backup seminggu sekali pada hari Minggu pukul 02:00 pagi
        $schedule->command('backup:run')->weeklyOn(0, '02:00');
        $schedule->command('logs:rotate-activity')->weeklyOn(1, '01:00');
        // Anda juga bisa membersihkan backup lama secara berkala
        // $schedule->command('backup:clean')->daily();
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