<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskCommentNotification extends Notification
{
    use Queueable;

    protected TaskComment $comment;
    protected Task $task;
    protected User $commenter;

    /**
     * Create a new notification instance.
     */
    public function __construct(TaskComment $comment, Task $task, User $commenter)
    {
        $this->comment = $comment;
        $this->task = $task;
        $this->commenter = $commenter;
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
        return [
            'notification_type' => 'task_comment',
            'title' => 'New Comment on Task',
            'message' => $this->commenter->name . ' commented on "' . $this->task->title . '"',
            'icon' => 'fas fa-comment',
            'url' => '/admin/tasks/kanban?project_id=' . $this->task->project_id,
            'comment_id' => $this->comment->id,
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'comment_preview' => \Illuminate\Support\Str::limit($this->comment->comment, 100),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
