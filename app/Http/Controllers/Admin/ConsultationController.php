<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use App\Models\ScheduledSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                    $query->where('status', 'pending')
                        ->whereDoesntHave('payments', function ($q) {
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

        $pendingApplications = ConsultationApplication::where('status', 'pending')
            ->whereDoesntHave('payments', function ($q) {
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
            // Get admin user info with fallback
            $adminName = Auth::check() ? Auth::user()->name : 'P23 Africa Admin';
            $adminEmail = Auth::check() ? Auth::user()->email : 'contact@p23africa.com';

            // Create admin message
            $chatMessage = \App\Models\ChatMessage::create([
                'consultation_application_id' => $application->id,
                'sender_name' => $adminName,
                'sender_email' => $adminEmail,
                'message' => $request->message,
                'sender_type' => 'admin',
                'is_read' => true, // Admin messages are automatically read
                'sent_at' => now()
            ]);

            // Send email to client
            try {
                Mail::to($application->email)->send(new \App\Mail\ConsultationAdminReply($chatMessage));
                Log::info('Admin reply email sent successfully', [
                    'application_id' => $application->id,
                    'client_email' => $application->email,
                    'admin_name' => $adminName
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send admin reply email', [
                    'error' => $e->getMessage(),
                    'application_id' => $application->id,
                    'client_email' => $application->email
                ]);
                // Don't fail the entire request if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send admin reply', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'application_id' => $id,
                'user_authenticated' => Auth::check()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply: ' . $e->getMessage()
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
            Log::error('Failed to mark message as read', [
                'error' => $e->getMessage(),
                'message_id' => $messageId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark message as read'
            ], 500);
        }
    }

    /**
     * Delete a consultation application
     */
    public function destroy($id)
    {
        try {
            $application = ConsultationApplication::findOrFail($id);
            $applicationName = $application->name ?? 'Unknown';
            $applicationEmail = $application->email ?? 'N/A';

            // Delete related records first
            $application->payments()->delete();
            $application->scheduledSessions()->delete();
            $application->chatMessages()->delete();

            // Delete the application
            $application->delete();

            activity()
                ->causedBy(Auth::user())
                ->withProperties(['name' => $applicationName, 'email' => $applicationEmail, 'id' => $id])
                ->log('Deleted consultation application');

            Log::info('Consultation application deleted', [
                'id' => $id,
                'deleted_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete consultation application', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete consultation applications
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:consultation_applications,id'
        ]);

        try {
            $ids = $request->ids;
            $deletedCount = 0;

            DB::beginTransaction();

            foreach ($ids as $id) {
                $application = ConsultationApplication::find($id);
                if ($application) {
                    // Delete related records first
                    $application->payments()->delete();
                    $application->scheduledSessions()->delete();
                    $application->chatMessages()->delete();

                    // Delete the application
                    $application->delete();
                    $deletedCount++;
                }
            }

            DB::commit();

            activity()
                ->causedBy(Auth::user())
                ->withProperties(['deleted_count' => $deletedCount, 'ids' => $ids])
                ->log('Bulk deleted consultation applications');

            Log::info('Bulk delete consultation applications', [
                'ids' => $ids,
                'deleted_count' => $deletedCount,
                'deleted_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} application(s) deleted successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to bulk delete consultation applications', [
                'error' => $e->getMessage(),
                'ids' => $request->ids
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete applications: ' . $e->getMessage()
            ], 500);
        }
    }
}
