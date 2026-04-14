<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f5f7fb; font-family: Arial, sans-serif; color: #1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f7fb; padding: 24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb;">
                    <tr>
                        <td style="padding: 24px 24px 12px 24px;">
                            <h2 style="margin: 0 0 12px 0; font-size: 22px; color: #111827;">Hello {{ $notifiable->name }},</h2>
                            <p style="margin: 0 0 8px 0; font-size: 15px; line-height: 1.6; color: #374151;">
                                You have been assigned a new task by <strong>{{ $assignedBy->name }}</strong>.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 24px 8px 24px;">
                            <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #111827;">Task Details</h3>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 14px;">
                                <tr>
                                    <td style="padding: 6px 0; color: #6b7280; width: 120px;">Title</td>
                                    <td style="padding: 6px 0; color: #111827;"><strong>{{ $task->title }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6b7280;">Project</td>
                                    <td style="padding: 6px 0; color: #111827;">{{ $projectName }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6b7280;">Priority</td>
                                    <td style="padding: 6px 0; color: #111827;">{{ $priorityLabel }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6b7280;">Due Date</td>
                                    <td style="padding: 6px 0; color: #111827;">{{ $dueDate }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @if (!empty($taskDescriptionHtml))
                        <tr>
                            <td style="padding: 8px 24px 8px 24px;">
                                <h3 style="margin: 0 0 8px 0; font-size: 16px; color: #111827;">Description</h3>
                                <div style="font-size: 14px; line-height: 1.65; color: #374151;">
                                    {!! $taskDescriptionHtml !!}
                                </div>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding: 16px 24px 24px 24px;">
                            <a href="{{ $taskUrl }}" style="display: inline-block; padding: 10px 16px; background: #111827; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
                                View Task
                            </a>
                            <p style="margin: 14px 0 0 0; font-size: 13px; color: #6b7280;">Thank you for being part of the team.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
