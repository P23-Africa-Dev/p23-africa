<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LeaveRequestApproved;
use App\Mail\LeaveRequestModified;
use App\Mail\LeaveRequestRejected;
use App\Mail\LeaveRequestSenderResponse;
use App\Mail\LeaveRequestSubmitted;
use App\Models\LeaveRequest;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeaveRequestController extends Controller
{
    /**
     * Display leave requests list.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = LeaveRequest::with(['user', 'supervisor'])->orderBy('created_at', 'desc');

        // Scope to organization
        if ($user->organization_id && !$user->hasRole('admin')) {
            $query->where('organization_id', $user->organization_id);
        } elseif (!$user->hasRole('admin')) {
            $query->whereNull('organization_id');
        }

        // Staff can only see their own + those they supervise
        if ($user->hasRole('staff')) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('supervisor_id', $user->id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $leaveRequests = $query->paginate(20);

        return view('admin.leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Show create leave request form.
     */
    public function create()
    {
        $user = Auth::user();

        // Get supervisors/admins in the same organization for the dropdown
        $supervisors = $this->getSupervisors($user);

        return view('admin.leave-requests.create', compact('supervisors'));
    }

    /**
     * Store a new leave request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys(LeaveRequest::TYPES)),
            'reason' => 'required|string|max:2000',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'supervisor_id' => 'required|exists:users,id',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $durationDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $endDate) + 1;

        $leaveRequest = LeaveRequest::create([
            'user_id' => $user->id,
            'supervisor_id' => $validated['supervisor_id'],
            'organization_id' => $user->organization_id,
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_days' => $durationDays,
            'status' => 'pending',
        ]);

        // Send email to supervisor
        $supervisor = User::find($validated['supervisor_id']);
        $org = $user->organization;

        try {
            Mail::to($supervisor->email)->send(new LeaveRequestSubmitted($leaveRequest, $org));
        } catch (\Exception $e) {
            Log::error('Failed to send leave request email: ' . $e->getMessage());
        }

        return redirect()->to($this->adminUrl('leave-requests'))
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Show a single leave request.
     */
    public function show()
    {
        $leaveRequest = LeaveRequest::findOrFail(request()->route('leaveRequest'));
        $this->authorizeView($leaveRequest);
        $leaveRequest->load(['user', 'supervisor']);

        return view('admin.leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Supervisor modifies a leave request (edits with note and new dates).
     */
    public function modify(Request $request)
    {
        $leaveRequest = LeaveRequest::findOrFail(request()->route('leaveRequest'));
        $user = Auth::user();

        // Only the assigned supervisor can modify
        if ($leaveRequest->supervisor_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'You are not authorized to modify this leave request.');
        }

        $validated = $request->validate([
            'modified_start_date' => 'required|date',
            'modified_end_date' => 'required|date|after_or_equal:modified_start_date',
            'supervisor_note' => 'required|string|max:2000',
        ]);

        $modifiedStart = Carbon::parse($validated['modified_start_date']);
        $modifiedEnd = Carbon::parse($validated['modified_end_date']);
        $modifiedDuration = $modifiedStart->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $modifiedEnd) + 1;

        $leaveRequest->update([
            'status' => 'modified',
            'modified_start_date' => $modifiedStart,
            'modified_end_date' => $modifiedEnd,
            'modified_duration_days' => $modifiedDuration,
            'supervisor_note' => $validated['supervisor_note'],
        ]);

        // Email the sender about modifications
        $org = $leaveRequest->user->organization;
        try {
            Mail::to($leaveRequest->user->email)->send(new LeaveRequestModified($leaveRequest, $org));
        } catch (\Exception $e) {
            Log::error('Failed to send leave modification email: ' . $e->getMessage());
        }

        return redirect()->to($this->adminUrl('leave-requests/' . $leaveRequest->id))
            ->with('success', 'Leave request modified and sender notified.');
    }

    /**
     * Sender responds to modified leave request (Okay / Not Okay).
     */
    public function senderRespond(Request $request)
    {
        $leaveRequest = LeaveRequest::findOrFail(request()->route('leaveRequest'));
        $user = Auth::user();

        // Only the original requester can respond
        if ($leaveRequest->user_id !== $user->id) {
            abort(403, 'You are not authorized to respond to this leave request.');
        }

        if ($leaveRequest->status !== 'modified') {
            return back()->with('error', 'This leave request is not awaiting your response.');
        }

        $validated = $request->validate([
            'response' => 'required|in:okay,not_okay',
            'sender_response_note' => 'nullable|string|max:2000',
        ]);

        $isOkay = $validated['response'] === 'okay';

        $leaveRequest->update([
            'status' => $isOkay ? 'sender_okay' : 'sender_not_okay',
            'sender_response_note' => $validated['sender_response_note'],
            'sender_responded_at' => now(),
        ]);

        // Email the supervisor about the sender's response
        $org = $leaveRequest->user->organization;
        try {
            Mail::to($leaveRequest->supervisor->email)->send(new LeaveRequestSenderResponse($leaveRequest, $isOkay, $org));
        } catch (\Exception $e) {
            Log::error('Failed to send sender response email: ' . $e->getMessage());
        }

        return redirect()->to($this->adminUrl('leave-requests/' . $leaveRequest->id))
            ->with('success', 'Your response has been sent to your supervisor.');
    }

    /**
     * Supervisor approves a leave request.
     */
    public function approve(Request $request)
    {
        $leaveRequest = LeaveRequest::findOrFail(request()->route('leaveRequest'));
        $user = Auth::user();

        if ($leaveRequest->supervisor_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'You are not authorized to approve this leave request.');
        }

        $validated = $request->validate([
            'decision_note' => 'nullable|string|max:2000',
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'decided_at' => now(),
            'decision_note' => $validated['decision_note'] ?? null,
        ]);

        // Email the sender
        $org = $leaveRequest->user->organization;
        try {
            Mail::to($leaveRequest->user->email)->send(new LeaveRequestApproved($leaveRequest, $org));
        } catch (\Exception $e) {
            Log::error('Failed to send leave approved email: ' . $e->getMessage());
        }

        return redirect()->to($this->adminUrl('leave-requests/' . $leaveRequest->id))
            ->with('success', 'Leave request approved.');
    }

    /**
     * Supervisor rejects a leave request.
     */
    public function reject(Request $request)
    {
        $leaveRequest = LeaveRequest::findOrFail(request()->route('leaveRequest'));
        $user = Auth::user();

        if ($leaveRequest->supervisor_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'You are not authorized to reject this leave request.');
        }

        $validated = $request->validate([
            'decision_note' => 'nullable|string|max:2000',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'decided_at' => now(),
            'decision_note' => $validated['decision_note'] ?? null,
        ]);

        // Email the sender
        $org = $leaveRequest->user->organization;
        try {
            Mail::to($leaveRequest->user->email)->send(new LeaveRequestRejected($leaveRequest, $org));
        } catch (\Exception $e) {
            Log::error('Failed to send leave rejected email: ' . $e->getMessage());
        }

        return redirect()->to($this->adminUrl('leave-requests/' . $leaveRequest->id))
            ->with('success', 'Leave request rejected.');
    }

    /**
     * Get leave request data for the calendar (JSON API).
     */
    public function calendarData(Request $request)
    {
        $user = Auth::user();
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());

        $query = LeaveRequest::with('user')
            ->where('status', 'approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            });

        if ($user->organization_id && !$user->hasRole('admin')) {
            $query->where('organization_id', $user->organization_id);
        }

        $leaves = $query->get()->map(function ($leave) {
            $effectiveStart = $leave->modified_start_date ?? $leave->start_date;
            $effectiveEnd = $leave->modified_end_date ?? $leave->end_date;

            return [
                'title' => $leave->user->name . ' — ' . $leave->type_name,
                'start' => $effectiveStart->toDateString(),
                'end' => $effectiveEnd->addDay()->toDateString(), // FullCalendar end is exclusive
                'color' => $this->getLeaveTypeColor($leave->type),
                'type' => 'leave',
                'url' => $this->adminUrl('leave-requests/' . $leave->id),
            ];
        });

        return response()->json($leaves);
    }

    /**
     * Attendance & Leave Calendar page.
     */
    public function calendar()
    {
        return view('admin.attendance.calendar');
    }

    /**
     * Get supervisors available for the current user.
     */
    private function getSupervisors(User $user)
    {
        $query = User::where('id', '!=', $user->id)->orderBy('name');

        if ($user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        }

        // Get users with supervisor or admin role
        $query->where(function ($q) {
            $q->role('supervisor')->orWhere(function ($q2) {
                $q2->role('admin');
            });
        });

        return $query->get();
    }

    /**
     * Authorization check for viewing a leave request.
     */
    private function authorizeView(LeaveRequest $leaveRequest): void
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return;
        }

        // User must be the requester, supervisor, or in the same org as a supervisor
        if ($leaveRequest->user_id === $user->id || $leaveRequest->supervisor_id === $user->id) {
            return;
        }

        if ($user->hasRole('supervisor') && $user->organization_id === $leaveRequest->organization_id) {
            return;
        }

        abort(403, 'You are not authorized to view this leave request.');
    }

    /**
     * Build the correct admin URL, respecting the org-slug prefix when present.
     */
    private function adminUrl(string $path = ''): string
    {
        $user = Auth::user();
        $prefix = '/admin';

        if ($user && $user->organization_id && !$user->hasRole('admin')) {
            $org = $user->organization;
            if ($org && $org->slug) {
                $prefix = '/' . $org->slug . '/admin';
            }
        }

        return url($prefix . ($path ? '/' . ltrim($path, '/') : ''));
    }

    /**
     * Get color for leave type (for calendar display).
     */
    private function getLeaveTypeColor(string $type): string
    {
        return match ($type) {
            'annual' => '#3b82f6',
            'sick' => '#ef4444',
            'personal' => '#8b5cf6',
            'maternity' => '#ec4899',
            'paternity' => '#06b6d4',
            'bereavement' => '#6b7280',
            default => '#f59e0b',
        };
    }
}
