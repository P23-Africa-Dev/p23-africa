<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
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
     * Get unread notification count
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
     * Mark all task notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->unreadNotifications()
            ->where('type', 'App\Notifications\TaskAssignedNotification')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }
}
