<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

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
        $taskUrl = route('admin.projects.show', $this->task->project_id);
        $taskDescriptionHtml = $this->sanitizeDescriptionHtml($this->task->description);

        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->view('emails.notifications.task-assigned', [
                'notifiable' => $notifiable,
                'assignedBy' => $this->assignedBy,
                'task' => $this->task,
                'projectName' => $projectName,
                'priorityLabel' => $priorityLabel,
                'dueDate' => $dueDate,
                'taskUrl' => $taskUrl,
                'taskDescriptionHtml' => $taskDescriptionHtml,
            ]);
    }

    /**
     * Preserve basic rich-text formatting while removing unsafe markup.
     */
    protected function sanitizeDescriptionHtml(?string $description): ?string
    {
        if (blank($description)) {
            return null;
        }

        $allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><blockquote><a>';
        $sanitized = strip_tags($description, $allowedTags);

        // Remove script/style blocks and javascript: URLs defensively.
        $sanitized = preg_replace('/<(script|style)\b[^>]*>(.*?)<\/\1>/is', '', $sanitized) ?? $sanitized;
        $sanitized = preg_replace('/(<a\s[^>]*href\s*=\s*["\'])\s*javascript:[^"\']*(["\'])/i', '$1#$2', $sanitized) ?? $sanitized;

        return Str::limit(trim($sanitized), 2000);
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
