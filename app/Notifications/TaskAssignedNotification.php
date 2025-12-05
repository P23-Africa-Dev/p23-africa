<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected Task $task;
    protected User $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, User $assignedBy)
    {
        $this->task = $task;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $projectName = $this->task->project?->name ?? 'Unknown Project';
        $priorityLabel = ucfirst($this->task->priority);
        $dueDate = $this->task->due_date ? $this->task->due_date->format('M d, Y') : 'No due date';

        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned a new task by ' . $this->assignedBy->name . '.')
            ->line('')
            ->line('**Task Details:**')
            ->line('• **Title:** ' . $this->task->title)
            ->line('• **Project:** ' . $projectName)
            ->line('• **Priority:** ' . $priorityLabel)
            ->line('• **Due Date:** ' . $dueDate)
            ->line('')
            ->when($this->task->description, function ($mail) {
                return $mail->line('**Description:** ' . \Illuminate\Support\Str::limit($this->task->description, 200));
            })
            ->action('View Task', route('admin.projects.show', $this->task->project_id))
            ->line('Thank you for being part of the team!');
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'notification_type' => 'task_assigned',
            'title' => 'New Task Assigned',
            'message' => $this->assignedBy->name . ' assigned you a task: ' . $this->task->title,
            'icon' => 'fas fa-user-plus',
            'url' => '/admin/tasks/kanban?project_id=' . $this->task->project_id,
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'project_name' => $this->task->project?->name,
            'assigned_by_id' => $this->assignedBy->id,
            'assigned_by_name' => $this->assignedBy->name,
            'priority' => $this->task->priority,
            'due_date' => $this->task->due_date?->toDateString(),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
