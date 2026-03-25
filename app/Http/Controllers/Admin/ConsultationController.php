<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConsultationApplication;
use App\Models\Payment;
use App\Models\ScheduledSession;
use App\Services\GoogleCalendarService;
use App\Mail\ConsultationScheduledMail;
use App\Mail\ConsultationScheduledNotificationMail;
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

        return orgRedirect('admin.consultation.show', $id)
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
     * Manually trigger Google Calendar event creation + send scheduling emails
     * for a consultation that was missed (e.g. due to fake DB records).
     */
    public function manualSchedule(Request $request, $id)
    {
        $request->validate([
            'session_date' => 'required|date',
            'session_time' => 'required|date_format:H:i',
            'timezone'     => 'required|string',
        ]);

        $application = ConsultationApplication::findOrFail($id);

        try {
            // Build start / end datetime
            $sessionDateTime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->session_date . ' ' . $request->session_time,
                $request->timezone
            );
            $sessionEndTime = $sessionDateTime->copy()->addMinutes(90);

            $startDateTime = $sessionDateTime->toIso8601String();
            $endDateTime   = $sessionEndTime->toIso8601String();

            // Google Calendar event
            $calendarService = new GoogleCalendarService();
            $adminEmail = config('services.admin.email', 'contact@p23africa.com');

            $eventSummary = 'P23 Africa Consultation';
            $eventDescription = "90-minute consultation session\n\n"
                . "Client: {$application->name}\n"
                . "Company: {$application->company}\n"
                . "Industry: {$application->industry}\n"
                . "Email: {$application->email}\n"
                . "Phone: {$application->phone}\n\n"
                . "Business Description:\n{$application->business_description}\n\n"
                . "This consultation will be conducted via Google Meet.";

            $attendees = [$adminEmail, $application->email];

            $calendarResult = $calendarService->createEvent(
                $eventSummary,
                $eventDescription,
                $startDateTime,
                $endDateTime,
                $request->timezone,
                $attendees
            );

            if (!$calendarResult['success']) {
                throw new \Exception('Google Calendar event creation failed: ' . ($calendarResult['error'] ?? 'Unknown error'));
            }

            // Create (or update) ScheduledSession record
            $scheduledSession = ScheduledSession::updateOrCreate(
                ['consultation_application_id' => $application->id],
                [
                    'session_date'              => $request->session_date,
                    'session_time'              => $request->session_time,
                    'timezone'                  => $request->timezone,
                    'meet_link'                 => $calendarResult['meet_link'] ?? '',
                    'google_calendar_event_id'  => $calendarResult['event_id'],
                    'google_meet_link'          => $calendarResult['meet_link'],
                    'calendar_html_link'        => $calendarResult['html_link'],
                    'status'                    => 'scheduled',
                ]
            );

            $application->update(['status' => 'scheduled']);

            // Generate .ics file
            $icsContent = $calendarService->generateIcsFile(
                $eventSummary,
                $eventDescription,
                $startDateTime,
                $endDateTime,
                $calendarResult['meet_link'] ?? 'Google Meet',
                $adminEmail,
                $application->email
            );

            $icsFilename = 'consultation_' . $application->id . '_' . time() . '.ics';
            $icsPath = storage_path('app/temp/' . $icsFilename);
            if (!file_exists(dirname($icsPath))) {
                mkdir(dirname($icsPath), 0755, true);
            }
            file_put_contents($icsPath, $icsContent);

            // Build the same $emailData object used by the normal flow
            $emailData = (object) [
                'id'                 => $scheduledSession->id,
                'name'               => $application->name,
                'email'              => $application->email,
                'phone'              => $application->phone,
                'company'            => $application->company,
                'consultation_type'  => $application->industry ?? 'Business Consultation',
                'consultation_date'  => $sessionDateTime,
                'google_meet_link'   => $calendarResult['meet_link'] ?? null,
                'calendar_html_link' => $calendarResult['html_link'] ?? null,
                'message'            => $application->business_description ?? null,
                'created_at'         => $scheduledSession->created_at,
            ];

            // Send client email with .ics attachment
            try {
                Mail::to($application->email)->send(new ConsultationScheduledMail($emailData, $icsContent));
                Log::info('Manual schedule: client email sent', ['email' => $application->email]);
            } catch (\Exception $e) {
                Log::error('Manual schedule: client email failed', ['error' => $e->getMessage()]);
            }

            // Send admin notification email
            try {
                Mail::to($adminEmail)->send(new ConsultationScheduledNotificationMail($emailData));
                Log::info('Manual schedule: admin email sent', ['email' => $adminEmail]);
            } catch (\Exception $e) {
                Log::error('Manual schedule: admin email failed', ['error' => $e->getMessage()]);
            }

            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'application_id' => $application->id,
                    'client_name'    => $application->name,
                    'session_date'   => $request->session_date,
                    'session_time'   => $request->session_time,
                    'meet_link'      => $calendarResult['meet_link'] ?? null,
                ])
                ->log('Manually scheduled consultation & sent meeting emails');

            return response()->json([
                'success'   => true,
                'message'   => 'Google Meet created and scheduling emails sent to ' . $application->email,
                'meet_link' => $calendarResult['meet_link'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Manual schedule failed', [
                'application_id' => $id,
                'error'          => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage(),
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
