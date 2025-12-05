<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    protected string $type;
    protected string $title;
    protected string $message;
    protected ?string $icon;
    protected ?string $url;
    protected array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $type,
        string $title,
        string $message,
        ?string $url = null,
        ?string $icon = null,
        array $data = []
    ) {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->icon = $icon ?? $this->getDefaultIcon($type);
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return array_merge([
            'notification_type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'url' => $this->url,
        ], $this->data);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }

    /**
     * Get default icon based on notification type
     */
    protected function getDefaultIcon(string $type): string
    {
        return match ($type) {
            'brn_application' => 'fas fa-file-alt',
            'consultation' => 'fas fa-calendar-check',
            'contact_form' => 'fas fa-envelope',
            'event_feedback' => 'fas fa-star',
            'playbook_download' => 'fas fa-download',
            'project_created' => 'fas fa-project-diagram',
            'task_created' => 'fas fa-tasks',
            'task_assigned' => 'fas fa-user-plus',
            'task_comment' => 'fas fa-comment',
            default => 'fas fa-bell',
        };
    }
}
