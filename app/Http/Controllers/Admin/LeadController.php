<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadDrive;
use App\Models\LeadEmail;
use App\Models\User;
use App\Models\ContactSubmission;
use App\Models\ConsultationApplication;
use App\Mail\LeadMail;
use App\Services\LeadEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    /**
     * Ensure the current user can access the given lead based on organization.
     */
    protected function ensureLeadOrgAccess(Lead $lead, $user)
    {
        if ($user && $user->hasRole('admin')) {
            return;
        }

        if ($user) {
            if ($user->organization_id) {
                if ($lead->organization_id !== $user->organization_id) {
                    abort(403, 'You are not authorized to access this lead.');
                }
            } else {
                if (!is_null($lead->organization_id)) {
                    abort(403, 'You are not authorized to access this lead.');
                }
            }
        } else {
            // Unauthenticated users cannot access leads
            abort(403, 'You are not authorized to access this lead.');
        }
    }

    /**
     * Display a listing of leads.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Lead::with(['assignedUser', 'drive', 'activities' => function ($q) {
            $q->latest()->limit(1);
        }])->orderBy('created_at', 'desc');

        // If not a global admin, scope leads to user's organization
        if ($user && !$user->hasRole('admin')) {
            if ($user->organization_id) {
                $query->where('organization_id', $user->organization_id);
            } else {
                // users without org should see none
                $query->whereNull('organization_id');
            }
        }

        // Filter by drive
        if ($request->filled('drive')) {
            if ($request->drive === 'all') {
                // Show all leads
            } elseif ($request->drive === 'unassigned') {
                $query->whereNull('drive_id');
            } else {
                $query->where('drive_id', $request->drive);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by assigned user
        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter for overdue follow-ups
        if ($request->filled('overdue') && $request->overdue === 'true') {
            $query->overdueFollowUp();
        }

        $leads = $query->paginate(15)->appends($request->all());

        // Build stats query with same drive filter
        $statsQuery = Lead::query();
        if ($request->filled('drive') && $request->drive !== 'all') {
            if ($request->drive === 'unassigned') {
                $statsQuery->whereNull('drive_id');
            } else {
                $statsQuery->where('drive_id', $request->drive);
            }
        }

        // Get statistics
        $stats = [
            'total_leads' => (clone $statsQuery)->count(),
            'new_leads' => (clone $statsQuery)->new()->count(),
            'active_leads' => (clone $statsQuery)->active()->count(),
            'won_leads' => (clone $statsQuery)->won()->count(),
            'lost_leads' => (clone $statsQuery)->lost()->count(),
            'overdue_follow_ups' => (clone $statsQuery)->overdueFollowUp()->count(),
            'total_value' => (clone $statsQuery)->active()->sum('estimated_value'),
            'won_value' => (clone $statsQuery)->won()->sum('estimated_value'),
            'this_month_leads' => (clone $statsQuery)->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_month_won' => (clone $statsQuery)->won()
                ->whereMonth('converted_at', now()->month)
                ->whereYear('converted_at', now()->year)
                ->count(),
        ];

        // Get users for assignment filter
        $usersQuery = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'supervisor', 'staff']);
        });
        if ($user && !$user->hasRole('admin')) {
            $usersQuery->where('organization_id', $user->organization_id);
        }
        $users = $usersQuery->get();

        // Get drives for filter
        $drives = LeadDrive::orderBy('name')->get();
        $currentDrive = $request->filled('drive') && $request->drive !== 'all' && $request->drive !== 'unassigned'
            ? LeadDrive::find($request->drive)
            : null;

        return view('admin.crm.index', compact('leads', 'stats', 'users', 'drives', 'currentDrive'));
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'supervisor', 'staff']);
        })->get();

        return view('admin.crm.create', [
            'users' => $users,
            'statuses' => Lead::getStatuses(),
            'sources' => Lead::getSources(),
            'priorities' => Lead::getPriorities(),
            'services' => Lead::getServices(),
            'industries' => Lead::getIndustries(),
        ]);
    }

    /**
     * Store a newly created lead.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'year_founded' => 'nullable|integer|min:1901|max:' . date('Y'),
            'industry' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:255',
            'website' => 'nullable|url:http,https|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(array_keys(Lead::getStatuses()))],
            'source' => ['required', Rule::in(array_keys(Lead::getSources()))],
            'priority' => ['required', Rule::in(array_keys(Lead::getPriorities()))],
            'estimated_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'interested_services' => 'nullable|array',
            'requirements' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:5000',
            'assigned_to' => 'nullable|exists:users,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'next_follow_up' => 'nullable|date|after:now',
        ]);

        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'assigned_to',
            'last_name',
            'phone',
            'company',
            'year_founded',
            'industry',
            'job_title',
            'website',
            'country',
            'city',
            'address',
            'estimated_value',
            'currency',
            'requirements',
            'notes',
            'next_follow_up'
        ];
        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        try {
            DB::beginTransaction();

            // Ensure organization is set: prefer passed org (admin), fallback to creator's org
            if (empty($validated['organization_id'])) {
                $validated['organization_id'] = auth()->user()->organization_id ?? null;
            }

            $lead = Lead::create($validated);

            // Log lead creation
            $lead->logActivity('note', 'Lead created', 'Lead was created manually by ' . auth()->user()->name);

            DB::commit();

            return redirect()->route('admin.crm.show', $lead)
                ->with('success', 'Lead created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create lead: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to create lead. Please try again.');
        }
    }

    /**
     * Display the specified lead.
     */
    public function show(Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $lead->load(['assignedUser', 'activities.user']);

        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'supervisor', 'staff']);
        })->get();

        return view('admin.crm.show', [
            'lead' => $lead,
            'users' => $users,
            'statuses' => Lead::getStatuses(),
            'priorities' => Lead::getPriorities(),
            'activityTypes' => LeadActivity::getTypes(),
        ]);
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit(Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'supervisor', 'staff']);
        })->get();

        return view('admin.crm.edit', [
            'lead' => $lead,
            'users' => $users,
            'statuses' => Lead::getStatuses(),
            'sources' => Lead::getSources(),
            'priorities' => Lead::getPriorities(),
            'services' => Lead::getServices(),
            'industries' => Lead::getIndustries(),
        ]);
    }

    /**
     * Update the specified lead.
     */
    public function update(Request $request, Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('leads')->ignore($lead->id)],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'year_founded' => 'nullable|integer|min:1901|max:' . date('Y'),
            'industry' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:255',
            'website' => 'nullable|url:http,https|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(array_keys(Lead::getStatuses()))],
            'source' => ['required', Rule::in(array_keys(Lead::getSources()))],
            'priority' => ['required', Rule::in(array_keys(Lead::getPriorities()))],
            'estimated_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'interested_services' => 'nullable|array',
            'requirements' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:5000',
            'assigned_to' => 'nullable|exists:users,id',
            'next_follow_up' => 'nullable|date',
            'lost_reason' => 'nullable|string|max:500',
        ]);

        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'assigned_to',
            'lost_reason',
            'address',
            'last_name',
            'phone',
            'company',
            'year_founded',
            'industry',
            'job_title',
            'website',
            'country',
            'city',
            'estimated_value',
            'currency',
            'requirements',
            'notes',
            'next_follow_up'
        ];
        foreach ($nullableFields as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        try {
            DB::beginTransaction();

            $oldStatus = $lead->status;
            $lead->update($validated);

            // Log status change if changed
            if ($oldStatus !== $lead->status) {
                $lead->logStatusChange($oldStatus, $lead->status);

                // Update converted_at if won
                if ($lead->status === 'won' && !$lead->converted_at) {
                    $lead->update(['converted_at' => now()]);
                }
            }

            DB::commit();

            return redirect()->route('admin.crm.show', $lead)
                ->with('success', 'Lead updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update lead: ' . $e->getMessage(), [
                'lead_id' => $lead->id,
                'data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            // In development, show actual error message
            $errorMessage = config('app.debug')
                ? 'Failed to update lead: ' . $e->getMessage()
                : 'Failed to update lead. Please try again.';

            return back()->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        try {
            $lead->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lead deleted successfully!'
                ]);
            }

            return redirect()->route('admin.crm.index')
                ->with('success', 'Lead deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete lead: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete lead.'
                ], 500);
            }

            return back()->with('error', 'Failed to delete lead.');
        }
    }

    /**
     * Update lead status via AJAX
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Lead::getStatuses()))],
            'lost_reason' => 'nullable|required_if:status,lost|string|max:500',
        ]);

        try {
            $oldStatus = $lead->status;
            $lead->update([
                'status' => $validated['status'],
                'lost_reason' => $validated['lost_reason'] ?? null,
                'converted_at' => $validated['status'] === 'won' ? now() : $lead->converted_at,
            ]);

            // Log the status change
            $lead->logStatusChange($oldStatus, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'lead' => $lead->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update lead status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.'
            ], 500);
        }
    }

    /**
     * Assign lead to a user
     */
    public function assign(Request $request, Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $oldAssignee = $lead->assignedUser;
            $lead->update(['assigned_to' => $validated['assigned_to']]);

            $newAssignee = $lead->fresh()->assignedUser;
            $description = $oldAssignee
                ? "Reassigned from {$oldAssignee->name} to " . ($newAssignee ? $newAssignee->name : 'Unassigned')
                : "Assigned to " . ($newAssignee ? $newAssignee->name : 'Unassigned');

            $lead->logActivity('note', 'Lead assignment changed', $description);

            return response()->json([
                'success' => true,
                'message' => 'Lead assigned successfully!',
                'lead' => $lead->load('assignedUser')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to assign lead: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign lead.'
            ], 500);
        }
    }

    /**
     * Add activity to a lead
     */
    public function addActivity(Request $request, Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $validated = $request->validate([
            'type' => ['required', Rule::in(array_keys(LeadActivity::getTypes()))],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'scheduled_at' => 'nullable|date',
        ]);

        try {
            $activity = $lead->activities()->create([
                'user_id' => auth()->id(),
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'scheduled_at' => $validated['scheduled_at'],
            ]);

            // Update last contacted if it's a contact activity
            if (in_array($validated['type'], ['email', 'call', 'meeting'])) {
                $lead->update(['last_contacted_at' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Activity added successfully!',
                'activity' => $activity->load('user')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to add activity: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add activity.'
            ], 500);
        }
    }

    /**
     * Update an activity
     */
    public function updateActivity(Request $request, LeadActivity $activity)
    {
        $validated = $request->validate([
            'type' => ['sometimes', Rule::in(array_keys(LeadActivity::getTypes()))],
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'scheduled_at' => 'nullable|date',
        ]);

        try {
            $activity->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Activity updated successfully!',
                'activity' => $activity->fresh('user')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update activity: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update activity.'
            ], 500);
        }
    }

    /**
     * Complete an activity
     */
    public function completeActivity(LeadActivity $activity)
    {
        try {
            $activity->markAsCompleted();

            return response()->json([
                'success' => true,
                'message' => 'Activity marked as completed!',
                'activity' => $activity->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to complete activity: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete activity.'
            ], 500);
        }
    }

    /**
     * Set next follow-up date
     */
    public function setFollowUp(Request $request, Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $validated = $request->validate([
            'next_follow_up' => 'required|date|after:now',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            $lead->update(['next_follow_up' => $validated['next_follow_up']]);

            $lead->logActivity(
                'follow_up',
                'Follow-up scheduled',
                $validated['note'] ?? 'Follow-up scheduled for ' . $lead->next_follow_up->format('M d, Y h:i A'),
                ['scheduled_for' => $validated['next_follow_up']]
            );

            return response()->json([
                'success' => true,
                'message' => 'Follow-up scheduled successfully!',
                'lead' => $lead->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to set follow-up: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule follow-up.'
            ], 500);
        }
    }

    /**
     * Convert contact submission to lead
     */
    public function convertFromContact(ContactSubmission $contact)
    {
        // Check if already converted
        $existingLead = Lead::where('source_type', 'contact_submission')
            ->where('source_id', $contact->id)
            ->first();

        if ($existingLead) {
            return response()->json([
                'success' => false,
                'message' => 'This contact has already been converted to a lead.',
                'lead_id' => $existingLead->id
            ], 422);
        }

        // Check if lead with same email exists
        $emailLead = Lead::where('email', $contact->email)->first();
        if ($emailLead) {
            return response()->json([
                'success' => false,
                'message' => 'A lead with this email already exists.',
                'lead_id' => $emailLead->id
            ], 422);
        }

        try {
            DB::beginTransaction();

            $lead = Lead::createFromContactSubmission($contact);
            // Associate with current user's organization if available
            if (auth()->check() && auth()->user()->organization_id) {
                $lead->update(['organization_id' => auth()->user()->organization_id]);
            }
            $lead->logActivity('note', 'Lead created from contact submission', 'Converted from contact form submission #' . $contact->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Contact converted to lead successfully!',
                'lead' => $lead,
                'redirect' => route('admin.crm.show', $lead)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to convert contact to lead: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to convert contact to lead.'
            ], 500);
        }
    }

    /**
     * Convert consultation application to lead
     */
    public function convertFromConsultation(ConsultationApplication $consultation)
    {
        // Check if already converted
        $existingLead = Lead::where('source_type', 'consultation_application')
            ->where('source_id', $consultation->id)
            ->first();

        if ($existingLead) {
            return response()->json([
                'success' => false,
                'message' => 'This consultation has already been converted to a lead.',
                'lead_id' => $existingLead->id
            ], 422);
        }

        // Check if lead with same email exists
        $emailLead = Lead::where('email', $consultation->email)->first();
        if ($emailLead) {
            return response()->json([
                'success' => false,
                'message' => 'A lead with this email already exists.',
                'lead_id' => $emailLead->id
            ], 422);
        }

        try {
            DB::beginTransaction();

            $lead = Lead::createFromConsultation($consultation);
            if (auth()->check() && auth()->user()->organization_id) {
                $lead->update(['organization_id' => auth()->user()->organization_id]);
            }
            $lead->logActivity('note', 'Lead created from consultation', 'Converted from consultation application #' . $consultation->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Consultation converted to lead successfully!',
                'lead' => $lead,
                'redirect' => route('admin.crm.show', $lead)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to convert consultation to lead: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to convert consultation to lead.'
            ], 500);
        }
    }

    /**
     * Bulk update leads
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:leads,id',
            'action' => 'required|in:assign,status,priority,delete',
            'value' => 'nullable',
        ]);

        try {
            $user = auth()->user();

            $leads = Lead::whereIn('id', $validated['ids']);

            // Scope bulk actions to user's organization for non-admins
            if ($user && !$user->hasRole('admin')) {
                if ($user->organization_id) {
                    $leads->where('organization_id', $user->organization_id);
                } else {
                    $leads->whereNull('organization_id');
                }
            }

            $count = $leads->count();

            switch ($validated['action']) {
                case 'assign':
                    $leads->update(['assigned_to' => $validated['value']]);
                    $message = "{$count} leads assigned successfully!";
                    break;

                case 'status':
                    $leads->update(['status' => $validated['value']]);
                    $message = "{$count} leads status updated!";
                    break;

                case 'priority':
                    $leads->update(['priority' => $validated['value']]);
                    $message = "{$count} leads priority updated!";
                    break;

                case 'delete':
                    $leads->delete();
                    $message = "{$count} leads deleted!";
                    break;

                default:
                    return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed.'
            ], 500);
        }
    }

    /**
     * Export leads to CSV
     */
    public function export(Request $request)
    {
        $user = auth()->user();

        $query = Lead::with('assignedUser');

        // Scope to user's organization for non-admin users
        if ($user && !$user->hasRole('admin')) {
            if ($user->organization_id) {
                $query->where('organization_id', $user->organization_id);
            } else {
                $query->whereNull('organization_id');
            }
        }

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $leads = $query->get();

        $filename = 'leads-export-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Company',
                'Year Founded',
                'Industry',
                'Job Title',
                'Status',
                'Source',
                'Priority',
                'Estimated Value',
                'Currency',
                'Country',
                'City',
                'Assigned To',
                'Next Follow Up',
                'Last Contacted',
                'Created At'
            ]);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->full_name,
                    $lead->email,
                    $lead->phone,
                    $lead->company,
                    $lead->year_founded,
                    $lead->industry,
                    $lead->job_title,
                    $lead->status_label,
                    $lead->source_label,
                    $lead->priority_label,
                    $lead->estimated_value,
                    $lead->currency,
                    $lead->country,
                    $lead->city,
                    $lead->assignedUser?->name ?? 'Unassigned',
                    $lead->next_follow_up?->format('Y-m-d H:i'),
                    $lead->last_contacted_at?->format('Y-m-d H:i'),
                    $lead->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get pipeline view data
     */
    public function pipeline(Request $request)
    {
        $statuses = Lead::getStatuses();
        $pipeline = [];

        // Get drives for filter
        $drives = LeadDrive::orderBy('name')->get();
        $currentDrive = $request->filled('drive') && $request->drive !== 'all' && $request->drive !== 'unassigned'
            ? LeadDrive::find($request->drive)
            : null;

        $user = auth()->user();

        foreach ($statuses as $key => $label) {
            $query = Lead::where('status', $key)
                ->with(['assignedUser', 'drive']);

            // Apply drive filter
            if ($request->filled('drive') && $request->drive !== 'all') {
                if ($request->drive === 'unassigned') {
                    $query->whereNull('drive_id');
                } else {
                    $query->where('drive_id', $request->drive);
                }
            }

            // Scope to user's organization
            if ($user && !$user->hasRole('admin')) {
                if ($user->organization_id) {
                    $query->where('organization_id', $user->organization_id);
                } else {
                    $query->whereNull('organization_id');
                }
            }

            $leadsQuery = clone $query;
            $countQuery = clone $query;
            $valueQuery = clone $query;

            $pipeline[$key] = [
                'label' => $label,
                'leads' => $leadsQuery->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get(),
                'count' => $countQuery->count(),
                'value' => $valueQuery->sum('estimated_value'),
            ];
        }

        return view('admin.crm.pipeline', compact('pipeline', 'statuses', 'drives', 'currentDrive'));
    }

    /**
     * Get emails for a lead
     */
    public function getEmails(Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $emails = $lead->emails()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark incoming emails as read
        $lead->emails()
            ->incoming()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'emails' => $emails,
            'unread_count' => 0
        ]);
    }

    /**
     * Send email to a lead
     */
    public function sendEmail(Request $request, Lead $lead, LeadEmailService $emailService)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:10000',
            'reply_to_id' => 'nullable|exists:lead_emails,id',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Validate that user can send emails via Google Workspace
            if (!$emailService->canUserSendEmail($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your email address is not authorized to send emails. Please contact your administrator.'
                ], 403);
            }

            $replyTo = null;
            $threadId = null;
            $inReplyTo = null;

            // If this is a reply, get the thread info
            if ($validated['reply_to_id']) {
                $replyTo = LeadEmail::find($validated['reply_to_id']);
                if ($replyTo) {
                    $threadId = $replyTo->thread_id;
                    $inReplyTo = $replyTo->message_id;
                }
            }

            // Create the email record
            $leadEmail = LeadEmail::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'from_email' => $user->email,
                'from_name' => $user->name,
                'to_email' => $lead->email,
                'to_name' => $lead->full_name,
                'subject' => $validated['subject'],
                'body' => $validated['body'],
                'body_html' => nl2br(e($validated['body'])),
                'direction' => 'outgoing',
                'thread_id' => $threadId,
                'in_reply_to' => $inReplyTo,
                'status' => 'draft',
            ]);

            // Send the email using the service (handles Google Workspace configuration)
            $emailService->sendEmail($leadEmail, $user);

            // Update email status
            $leadEmail->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            // Log activity
            $lead->logActivity(
                'email',
                'Email sent: ' . $validated['subject'],
                'Email sent to ' . $lead->email . ' from ' . $user->email,
                ['email_id' => $leadEmail->id]
            );

            // Update last contacted timestamp
            $lead->update(['last_contacted_at' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully from ' . $user->email . '!',
                'email' => $leadEmail->fresh()->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to send lead email: ' . $e->getMessage(), [
                'lead_id' => $lead->id,
                'user_id' => auth()->id(),
                'sender_email' => auth()->user()->email ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            // If email record was created, mark as failed
            if (isset($leadEmail)) {
                $leadEmail->update(['status' => 'failed']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
        }
    }

    /**
     * Get a single email thread
     */
    public function getEmailThread(Lead $lead, $threadId)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $emails = $lead->emails()
            ->where('thread_id', $threadId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread incoming emails in this thread as read
        $lead->emails()
            ->where('thread_id', $threadId)
            ->incoming()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'emails' => $emails
        ]);
    }

    /**
     * Get unread email count for a lead
     */
    public function getUnreadEmailCount(Lead $lead)
    {
        $user = auth()->user();
        $this->ensureLeadOrgAccess($lead, $user);

        $count = $lead->emails()
            ->incoming()
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
