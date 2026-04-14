<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Tests\TestCase;

class TaskAssignedNotificationMailFormattingTest extends TestCase
{
    public function test_task_assignment_email_uses_html_view_with_sanitized_description(): void
    {
        $task = new Task([
            'title' => 'Quarterly Follow-up',
            'priority' => 'high',
            'project_id' => 7,
            'description' => '<p>Formatted paragraph</p><script>alert(1)</script><a href="javascript:alert(2)">Click</a>',
        ]);

        $task->setRelation('project', new Project(['name' => 'CRM Expansion']));

        $assignedBy = new User(['name' => 'Manager']);
        $notifiable = new User(['name' => 'Staff User']);

        $notification = new TaskAssignedNotification($task, $assignedBy);
        $mailMessage = $notification->toMail($notifiable);

        $this->assertSame('emails.notifications.task-assigned', $mailMessage->view);
        $this->assertNull($mailMessage->markdown);

        $this->assertArrayHasKey('taskDescriptionHtml', $mailMessage->viewData);
        $this->assertStringContainsString('<p>Formatted paragraph</p>', $mailMessage->viewData['taskDescriptionHtml']);
        $this->assertStringNotContainsString('<script>', $mailMessage->viewData['taskDescriptionHtml']);
        $this->assertStringContainsString('href="#"', $mailMessage->viewData['taskDescriptionHtml']);
    }
}
