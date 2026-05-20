<?php

namespace App\Providers;

use App\Events\WhatsAppConversationAssigned;
use App\Events\WhatsAppMessageReceived;
use App\Listeners\NotifyAssignedAdminOfIncomingWhatsAppMessage;
use App\Listeners\SendWhatsAppAssignmentNotification;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Only bind 'files' if it does not exist to avoid conflicts
        if (!$this->app->bound('files')) {
            $this->app->singleton('files', function ($app) {
                return new Filesystem;
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            WhatsAppMessageReceived::class,
            NotifyAssignedAdminOfIncomingWhatsAppMessage::class
        );

        Event::listen(
            WhatsAppConversationAssigned::class,
            SendWhatsAppAssignmentNotification::class
        );
    }
}
