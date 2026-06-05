<?php

namespace App\Console;

use App\Console\Commands\SendEventReminders;
use App\Console\Commands\SyncWhatsAppTemplatesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // 👇 Register your custom commands here
    protected $commands = [
        SendEventReminders::class,
        SyncWhatsAppTemplatesCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reminders:send')
            ->everyMinute()
            ->timezone(config('app.timezone'))
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));

        if ((bool) config('whatsapp.queue', true)) {
            $schedule->command('queue:work --queue=whatsapp --stop-when-empty --tries=5 --max-time=50')
                ->everyMinute()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/whatsapp-queue.log'));
        }

        if ((bool) config('whatsapp.template_sync.enabled', true)) {
            $onlyApproved = (bool) config('whatsapp.template_sync.only_approved', false);

            $schedule->command('whatsapp:sync-templates --only-approved=' . ($onlyApproved ? '1' : '0'))
                ->everyFifteenMinutes()
                ->timezone(config('app.timezone'))
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/whatsapp-template-sync.log'));
        }
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
