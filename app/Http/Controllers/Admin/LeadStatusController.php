<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeadStatusController extends Controller
{
    /**
     * Resolve the organization ID for the current user.
     */
    protected function orgId(): ?int
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return null; // super-admin manages global statuses
        }

        return $user->organization_id;
    }

    /**
     * Display the lead statuses management page.
     */
    public function index()
    {
        $orgId = $this->orgId();
        $statuses = LeadStatus::forOrganization($orgId)
            ->orderBy('position')
            ->get()
            ->map(function ($status) use ($orgId) {
                $query = \App\Models\Lead::where('status', $status->slug);
                if ($orgId) {
                    $query->where('organization_id', $orgId);
                } else {
                    $query->whereNull('organization_id');
                }
                $status->leads_count = $query->count();
                return $status;
            });

        return view('admin.crm.statuses.index', compact('statuses'));
    }

    /**
     * Store a new lead status.
     */
    public function store(Request $request)
    {
        $orgId = $this->orgId();

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'color'      => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
            'is_won'     => 'nullable|boolean',
            'is_lost'    => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);

        // Ensure unique slug within the organization
        $existing = LeadStatus::forOrganization($orgId)->where('slug', $slug)->exists();
        if ($existing) {
            return back()->with('error', 'A status with this name already exists.');
        }

        // Cannot be both won and lost
        if (!empty($validated['is_won']) && !empty($validated['is_lost'])) {
            return back()->with('error', 'A status cannot be both Won and Lost.');
        }

        try {
            DB::beginTransaction();

            // If setting as default, unset others
            if (!empty($validated['is_default'])) {
                LeadStatus::forOrganization($orgId)->where('is_default', true)->update(['is_default' => false]);
            }

            // Get next position
            $maxPosition = LeadStatus::forOrganization($orgId)->max('position') ?? 0;

            LeadStatus::create([
                'organization_id' => $orgId,
                'name'            => $validated['name'],
                'slug'            => $slug,
                'color'           => $validated['color'],
                'position'        => $maxPosition + 1,
                'is_default'      => !empty($validated['is_default']),
                'is_won'          => !empty($validated['is_won']),
                'is_lost'         => !empty($validated['is_lost']),
            ]);

            DB::commit();

            return orgRedirect('admin.crm.statuses.index')
                ->with('success', 'Lead status created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create lead status: ' . $e->getMessage());

            return back()->with('error', 'Failed to create lead status.');
        }
    }

    /**
     * Update an existing lead status.
     */
    public function update(Request $request, LeadStatus $leadStatus)
    {
        $orgId = $this->orgId();

        // Ensure the status belongs to this organization
        if ($leadStatus->organization_id !== $orgId) {
            abort(403, 'You are not authorized to modify this status.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'color'      => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
            'is_won'     => 'nullable|boolean',
            'is_lost'    => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);

        // Ensure unique slug within the organization (excluding self)
        $existing = LeadStatus::forOrganization($orgId)
            ->where('slug', $slug)
            ->where('id', '!=', $leadStatus->id)
            ->exists();
        if ($existing) {
            return back()->with('error', 'A status with this name already exists.');
        }

        // Cannot be both won and lost
        if (!empty($validated['is_won']) && !empty($validated['is_lost'])) {
            return back()->with('error', 'A status cannot be both Won and Lost.');
        }

        try {
            DB::beginTransaction();

            // If setting as default, unset others
            if (!empty($validated['is_default'])) {
                LeadStatus::forOrganization($orgId)
                    ->where('is_default', true)
                    ->where('id', '!=', $leadStatus->id)
                    ->update(['is_default' => false]);
            }

            $leadStatus->update([
                'name'       => $validated['name'],
                'slug'       => $slug,
                'color'      => $validated['color'],
                'is_default' => !empty($validated['is_default']),
                'is_won'     => !empty($validated['is_won']),
                'is_lost'    => !empty($validated['is_lost']),
            ]);

            DB::commit();

            return orgRedirect('admin.crm.statuses.index')
                ->with('success', 'Lead status updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update lead status: ' . $e->getMessage());

            return back()->with('error', 'Failed to update lead status.');
        }
    }

    /**
     * Delete a lead status.
     */
    public function destroy(LeadStatus $leadStatus)
    {
        $orgId = $this->orgId();

        // Ensure the status belongs to this organization
        if ($leadStatus->organization_id !== $orgId) {
            abort(403, 'You are not authorized to delete this status.');
        }

        // Prevent deleting a default status
        if ($leadStatus->is_default) {
            return orgRedirect('admin.crm.statuses.index')
                ->with('error', 'Cannot delete the default status. Set another status as default first.');
        }

        // Check if any leads use this status
        $leadsCount = \App\Models\Lead::where('status', $leadStatus->slug);
        if ($orgId) {
            $leadsCount->where('organization_id', $orgId);
        } else {
            $leadsCount->whereNull('organization_id');
        }

        if ($leadsCount->count() > 0) {
            return orgRedirect('admin.crm.statuses.index')
                ->with('error', 'Cannot delete this status because ' . $leadsCount->count() . ' leads are using it. Please move them to a different status first.');
        }

        try {
            $leadStatus->delete();

            return orgRedirect('admin.crm.statuses.index')
                ->with('success', 'Lead status deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete lead status: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete lead status.');
        }
    }

    /**
     * Reorder statuses via AJAX.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:lead_statuses,id',
        ]);

        $orgId = $this->orgId();

        try {
            DB::beginTransaction();

            foreach ($validated['order'] as $position => $statusId) {
                LeadStatus::where('id', $statusId)
                    ->where(function ($q) use ($orgId) {
                        if ($orgId) {
                            $q->where('organization_id', $orgId);
                        } else {
                            $q->whereNull('organization_id');
                        }
                    })
                    ->update(['position' => $position + 1]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status order updated successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reorder statuses: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder statuses.',
            ], 500);
        }
    }
}
