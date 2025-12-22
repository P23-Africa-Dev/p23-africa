<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;

class AdminNotificationService
{
    /**
     * Send notification to all admins
     */
    public static function notifyAdmins(
        string $type,
        string $title,
        string $message,
        ?string $url = null,
        ?string $icon = null,
        array $data = []
    ): void {
        $admins = User::role(['admin', 'supervisor'])->get();

        Notification::send($admins, new AdminNotification(
            $type,
            $title,
            $message,
            $url,
            $icon,
            $data
        ));
    }

    /**
     * Notify about new BRN/NOEL application
     */
    public static function notifyNewBrnApplication($submission): void
    {
        self::notifyAdmins(
            'brn_application',
            'New BRN Application',
            'New BRN application from ' . $submission->name . ' (' . $submission->company_name . ')',
            '/admin/brn-submissions',
            'fas fa-file-alt',
            [
                'submission_id' => $submission->id,
                'applicant_name' => $submission->name,
                'company_name' => $submission->company_name,
                'email' => $submission->email,
            ]
        );
    }

    /**
     * Notify about new consultation application
     */
    public static function notifyNewConsultation($consultation): void
    {
        self::notifyAdmins(
            'consultation',
            'New Consultation Request',
            'New consultation request from ' . $consultation->name,
            '/admin/consultations',
            'fas fa-calendar-check',
            [
                'consultation_id' => $consultation->id,
                'applicant_name' => $consultation->name,
                'email' => $consultation->email,
                'service_type' => $consultation->service_type ?? null,
            ]
        );
    }

    /**
     * Notify about new contact form submission
     */
    public static function notifyNewContactForm($submission): void
    {
        self::notifyAdmins(
            'contact_form',
            'New Contact Form Message',
            'New message from ' . $submission->name . ': ' . \Illuminate\Support\Str::limit($submission->subject ?? $submission->message, 50),
            '/admin/contact-submissions',
            'fas fa-envelope',
            [
                'submission_id' => $submission->id,
                'sender_name' => $submission->name,
                'email' => $submission->email,
                'subject' => $submission->subject ?? null,
            ]
        );
    }

    /**
     * Notify about new event feedback
     */
    public static function notifyNewEventFeedback($feedback): void
    {
        $eventName = $feedback->event?->name ?? 'Unknown Event';

        self::notifyAdmins(
            'event_feedback',
            'New Event Feedback',
            'New feedback for "' . $eventName . '" - Rating: ' . $feedback->rating . '/5',
            '/admin/events/' . ($feedback->event_id ?? '') . '/feedback',
            'fas fa-star',
            [
                'feedback_id' => $feedback->id,
                'event_id' => $feedback->event_id,
                'event_name' => $eventName,
                'rating' => $feedback->rating,
                'email' => $feedback->email,
            ]
        );
    }

    /**
     * Notify about new playbook download
     */
    public static function notifyPlaybookDownload($download): void
    {
        self::notifyAdmins(
            'playbook_download',
            'New Playbook Download',
            ($download->name ?? $download->email) . ' downloaded the playbook',
            '/admin/playbook-downloads',
            'fas fa-download',
            [
                'download_id' => $download->id,
                'name' => $download->name ?? null,
                'email' => $download->email,
                'company' => $download->company ?? null,
            ]
        );
    }

    /**
     * Notify about new project created
     */
    public static function notifyNewProject($project, $creator): void
    {
        self::notifyAdmins(
            'project_created',
            'New Project Created',
            $creator->name . ' created a new project: ' . $project->name,
            '/admin/projects/' . $project->id,
            'fas fa-project-diagram',
            [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'creator_id' => $creator->id,
                'creator_name' => $creator->name,
            ]
        );
    }

    /**
     * Notify about new task created
     */
    public static function notifyNewTask($task, $creator): void
    {
        $projectName = $task->project?->name ?? 'Unknown Project';

        self::notifyAdmins(
            'task_created',
            'New Task Created',
            $creator->name . ' created a task: ' . $task->title . ' in ' . $projectName,
            '/admin/tasks/kanban?project_id=' . $task->project_id,
            'fas fa-tasks',
            [
                'task_id' => $task->id,
                'task_title' => $task->title,
                'project_id' => $task->project_id,
                'project_name' => $projectName,
                'creator_id' => $creator->id,
                'creator_name' => $creator->name,
                'priority' => $task->priority,
            ]
        );
    }

    /**
     * Notify about new task comment
     */
    public static function notifyTaskComment($comment, $task, $commenter): void
    {
        // Get all task assignees except the commenter
        $assignees = $task->assignees->where('id', '!=', $commenter->id);

        // Also get the task creator if different from commenter
        if ($task->created_by && $task->created_by !== $commenter->id) {
            $creator = User::find($task->created_by);
            if ($creator && !$assignees->contains('id', $creator->id)) {
                $assignees->push($creator);
            }
        }

        // Notify assignees with TaskCommentNotification
        foreach ($assignees as $assignee) {
            $assignee->notify(new \App\Notifications\TaskCommentNotification($comment, $task, $commenter));
        }

        // Also notify admins with AdminNotification
        self::notifyAdmins(
            'task_comment',
            'New Task Comment',
            $commenter->name . ' commented on "' . $task->title . '"',
            '/admin/tasks/kanban?project_id=' . $task->project_id,
            'fas fa-comment',
            [
                'comment_id' => $comment->id,
                'task_id' => $task->id,
                'task_title' => $task->title,
                'project_id' => $task->project_id,
                'commenter_id' => $commenter->id,
                'commenter_name' => $commenter->name,
                'comment_preview' => \Illuminate\Support\Str::limit($comment->comment, 100),
            ]
        );
    }

    /**
     * Notify about new CRM lead
     */
    public static function notifyNewLead($lead): void
    {
        $sourceLabel = match ($lead->source) {
            'contact_form' => 'Contact Form',
            'consultation' => 'Consultation Request',
            'website' => 'Website',
            'referral' => 'Referral',
            'event' => 'Event',
            'social_media' => 'Social Media',
            'cold_outreach' => 'Cold Outreach',
            default => ucfirst(str_replace('_', ' ', $lead->source ?? 'Unknown')),
        };

        self::notifyAdmins(
            'new_lead',
            'New CRM Lead',
            'New lead from ' . $sourceLabel . ': ' . $lead->name . ($lead->company ? ' (' . $lead->company . ')' : ''),
            '/admin/crm/' . $lead->id,
            'fas fa-user-plus',
            [
                'lead_id' => $lead->id,
                'lead_name' => $lead->name,
                'email' => $lead->email,
                'company' => $lead->company,
                'source' => $lead->source,
                'status' => $lead->status,
            ]
        );
    }
}
