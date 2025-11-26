<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use App\Models\ScheduledSession;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * Display a listing of consultation applications
     */
    public function index(Request $request)
    {
        $query = ConsultationApplication::with(['payments', 'scheduledSessions'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->whereDoesntHave('payments', function ($q) {
                        $q->where('status', 'successful');
                    });
                    break;
                case 'paid':
                    $query->whereHas('payments', function ($q) {
                        $q->where('status', 'successful');
                    })->whereDoesntHave('scheduledSessions');
                    break;
                case 'scheduled':
                    $query->whereHas('scheduledSessions');
                    break;
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15);

        // Get statistics
        $stats = $this->getConsultationStats();

        return view('admin.consultation.index', compact('applications', 'stats'));
    }

    /**
     * Display the specified consultation application
     */
    public function show($id)
    {
        $application = ConsultationApplication::with(['payments', 'scheduledSessions', 'chatMessages', 'unreadChatMessages'])
            ->findOrFail($id);

        return view('admin.consultation.show', compact('application'));
    }

    /**
     * Update the status of a consultation application
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'notes' => 'nullable|string|max:1000'
        ]);

        $application = ConsultationApplication::findOrFail($id);

        // Update application status (you might want to add a status field to the table)
        // For now, we'll use notes or create a separate status tracking system

        return redirect()->route('admin.consultation.show', $id)
            ->with('success', 'Application status updated successfully.');
    }

    /**
     * Get consultation statistics for dashboard
     */
    public function getConsultationStats()
    {
        $totalApplications = ConsultationApplication::count();

        $pendingApplications = ConsultationApplication::whereDoesntHave('payments', function ($q) {
            $q->where('status', 'successful');
        })->count();

        $paidApplications = ConsultationApplication::whereHas('payments', function ($q) {
            $q->where('status', 'successful');
        })->whereDoesntHave('scheduledSessions')->count();

        $scheduledApplications = ConsultationApplication::whereHas('scheduledSessions')->count();

        $todayApplications = ConsultationApplication::whereDate('created_at', today())->count();

        $recentApplications = ConsultationApplication::where('created_at', '>=', now()->subDays(30))->count();

        return [
            'total_applications' => $totalApplications,
            'pending_applications' => $pendingApplications,
            'paid_applications' => $paidApplications,
            'scheduled_applications' => $scheduledApplications,
            'today_applications' => $todayApplications,
            'recent_applications' => $recentApplications,
        ];
    }

    /**
     * Send admin reply to consultation application
     */
    public function sendReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000'
        ]);

        $application = ConsultationApplication::findOrFail($id);

        try {
            // Create admin message
            $chatMessage = \App\Models\ChatMessage::create([
                'consultation_application_id' => $application->id,
                'sender_name' => auth()->user()->name ?? 'P23 Africa Admin',
                'sender_email' => auth()->user()->email,
                'message' => $request->message,
                'sender_type' => 'admin',
                'is_read' => true, // Admin messages are automatically read
                'sent_at' => now()
            ]);

            // Send email to client
            try {
                \Mail::to($application->email)->send(new \App\Mail\ConsultationAdminReply($chatMessage));
            } catch (\Exception $e) {
                \Log::error('Failed to send admin reply email', [
                    'error' => $e->getMessage(),
                    'application_id' => $application->id
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send admin reply', [
                'error' => $e->getMessage(),
                'application_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply'
            ], 500);
        }
    }

    /**
     * Mark chat message as read
     */
    public function markMessageAsRead($messageId)
    {
        try {
            $message = \App\Models\ChatMessage::findOrFail($messageId);
            $message->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to mark message as read', [
                'error' => $e->getMessage(),
                'message_id' => $messageId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark message as read'
            ], 500);
        }
    }
}
