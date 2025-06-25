<?php

namespace App\Console;

use App\Console\Commands\SendEventReminders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // 👇 Register your custom commands here
    protected $commands = [
        SendEventReminders::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Schedule your commands here
        $schedule->command('reminders:send')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
    
}
