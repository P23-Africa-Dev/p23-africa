<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the current user (admin gets all types, staff gets task-related)
     */
    public function getAllNotifications(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 30);

        $query = $user->notifications()->orderBy('created_at', 'desc');

        // Non-admin users only see task-related notifications
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereIn('type', [
                'App\Notifications\TaskAssignedNotification',
                'App\Notifications\TaskCommentNotification',
            ]);
        }

        $notifications = $query->take($limit)->get()->map(function ($notification) {
            return $this->formatNotification($notification);
        });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Get task notifications for the current user
     */
    public function getTaskNotifications(Request $request)
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->where('type', 'App\Notifications\TaskAssignedNotification')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'task_title' => $notification->data['task_title'] ?? 'Unknown Task',
                    'project_name' => $notification->data['project_name'] ?? '',
                    'priority' => $notification->data['priority'] ?? 'medium',
                    'due_date' => $notification->data['due_date'] ?? null,
                    'assigned_by' => $notification->data['assigned_by_name'] ?? 'Someone',
                    'task_id' => $notification->data['task_id'] ?? null,
                    'project_id' => $notification->data['project_id'] ?? null,
                    'message' => $notification->data['message'] ?? 'You have been assigned a new task',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_raw' => $notification->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Get all unread notification count
     */
    public function getAllUnreadCount()
    {
        $user = Auth::user();

        $query = $user->unreadNotifications();

        // Non-admin users only count task-related notifications
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereIn('type', [
                'App\Notifications\TaskAssignedNotification',
                'App\Notifications\TaskCommentNotification',
            ]);
        }

        return response()->json([
            'success' => true,
            'count' => $query->count(),
        ]);
    }

    /**
     * Get unread notification count (task notifications only - legacy)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();

        $count = $user->unreadNotifications()
            ->where('type', 'App\Notifications\TaskAssignedNotification')
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Get latest unread notifications for browser notification
     */
    public function getLatestUnread()
    {
        $user = Auth::user();

        $query = $user->unreadNotifications()->orderBy('created_at', 'desc');

        // Non-admin users only see task-related notifications
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereIn('type', [
                'App\Notifications\TaskAssignedNotification',
                'App\Notifications\TaskCommentNotification',
            ]);
        }

        $notifications = $query->take(5)->get()->map(function ($notification) {
            return $this->formatNotification($notification);
        });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found.',
        ], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $query = $user->unreadNotifications();

        // Non-admin users only mark task-related notifications
        if (!$user->hasRole(['admin', 'supervisor'])) {
            $query->whereIn('type', [
                'App\Notifications\TaskAssignedNotification',
                'App\Notifications\TaskCommentNotification',
            ]);
        }

        $query->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Format notification for API response
     */
    protected function formatNotification($notification): array
    {
        $data = $notification->data;

        return [
            'id' => $notification->id,
            'type' => $data['notification_type'] ?? $this->extractType($notification->type),
            'title' => $data['title'] ?? $this->getDefaultTitle($notification->type),
            'message' => $data['message'] ?? '',
            'icon' => $data['icon'] ?? 'fas fa-bell',
            'url' => $data['url'] ?? null,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at->diffForHumans(),
            'created_at_raw' => $notification->created_at->toISOString(),
            'data' => $data,
        ];
    }

    /**
     * Extract notification type from class name
     */
    protected function extractType(string $className): string
    {
        $parts = explode('\\', $className);
        $class = end($parts);
        return strtolower(preg_replace('/Notification$/', '', $class));
    }

    /**
     * Get default title based on notification type
     */
    protected function getDefaultTitle(string $className): string
    {
        return match ($className) {
            'App\Notifications\TaskAssignedNotification' => 'Task Assigned',
            'App\Notifications\TaskCommentNotification' => 'New Comment',
            'App\Notifications\AdminNotification' => 'Notification',
            default => 'New Notification',
        };
    }
}
