<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use App\Models\ContactSubmission;
use App\Models\ConsultationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    /**
     * Display a listing of leads.
     */
    public function index(Request $request)
    {
        $query = Lead::with(['assignedUser', 'activities' => function ($q) {
            $q->latest()->limit(1);
        }])->orderBy('created_at', 'desc');

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

        // Get statistics
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::new()->count(),
            'active_leads' => Lead::active()->count(),
            'won_leads' => Lead::won()->count(),
            'lost_leads' => Lead::lost()->count(),
            'overdue_follow_ups' => Lead::overdueFollowUp()->count(),
            'total_value' => Lead::active()->sum('estimated_value'),
            'won_value' => Lead::won()->sum('estimated_value'),
            'this_month_leads' => Lead::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_month_won' => Lead::won()
                ->whereMonth('converted_at', now()->month)
                ->whereYear('converted_at', now()->year)
                ->count(),
        ];

        // Get users for assignment filter
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'supervisor', 'staff']);
        })->get();

        return view('admin.crm.index', compact('leads', 'stats', 'users'));
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
            'job_title' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(array_keys(Lead::getStatuses()))],
            'source' => ['required', Rule::in(array_keys(Lead::getSources()))],
            'priority' => ['required', Rule::in(array_keys(Lead::getPriorities()))],
            'estimated_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'interested_services' => 'nullable|array',
            'requirements' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:5000',
            'assigned_to' => 'nullable|exists:users,id',
            'next_follow_up' => 'nullable|date|after:now',
        ]);

        try {
            DB::beginTransaction();

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
        ]);
    }

    /**
     * Update the specified lead.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('leads')->ignore($lead->id)],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(array_keys(Lead::getStatuses()))],
            'source' => ['required', Rule::in(array_keys(Lead::getSources()))],
            'priority' => ['required', Rule::in(array_keys(Lead::getPriorities()))],
            'estimated_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'interested_services' => 'nullable|array',
            'requirements' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:5000',
            'assigned_to' => 'nullable|exists:users,id',
            'next_follow_up' => 'nullable|date',
            'lost_reason' => 'nullable|string|max:500',
        ]);

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
            Log::error('Failed to update lead: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to update lead. Please try again.');
        }
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(Lead $lead)
    {
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
            $leads = Lead::whereIn('id', $validated['ids']);
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
        $query = Lead::with('assignedUser');

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
    public function pipeline()
    {
        $statuses = Lead::getStatuses();
        $pipeline = [];

        foreach ($statuses as $key => $label) {
            $pipeline[$key] = [
                'label' => $label,
                'leads' => Lead::where('status', $key)
                    ->with('assignedUser')
                    ->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get(),
                'count' => Lead::where('status', $key)->count(),
                'value' => Lead::where('status', $key)->sum('estimated_value'),
            ];
        }

        return view('admin.crm.pipeline', compact('pipeline', 'statuses'));
    }
}
