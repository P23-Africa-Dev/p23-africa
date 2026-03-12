<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeadDriveController extends Controller
{
    /**
     * Display drives management page
     */
    public function index()
    {
        $user = Auth::user();

        // Scope drives to user's organization (admins see all)
        $drivesQuery = LeadDrive::query()->orderBy('name');

        if (!$user->hasRole('admin')) {
            if ($user->organization_id) {
                $drivesQuery->where('organization_id', $user->organization_id);
                $drivesQuery->withCount(['leads' => function ($q) use ($user) {
                    $q->where('organization_id', $user->organization_id);
                }]);
            } else {
                $drivesQuery->whereNull('organization_id');
                $drivesQuery->withCount(['leads' => function ($q) {
                    $q->whereNull('organization_id');
                }]);
            }
        } else {
            $drivesQuery->withCount('leads');
        }

        $drives = $drivesQuery->get();

        return view('admin.crm.drives.index', compact('drives'));
    }

    /**
     * Store a new drive (form submission)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lead_drives,name',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'is_default' => 'nullable|boolean',
        ]);

        // If setting as default, remove default from others
        if (!empty($validated['is_default'])) {
            LeadDrive::where('is_default', true)->update(['is_default' => false]);
        }

        LeadDrive::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#0d6efd',
            'is_default' => !empty($validated['is_default']),
            'organization_id' => Auth::user()->organization_id ?? null,
        ]);

        return orgRedirect('admin.crm.drives.index')
            ->with('success', 'Drive created successfully!');
    }

    /**
     * Store a new drive via AJAX (for import modal)
     */
    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lead_drives,name',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
        ]);

        $drive = LeadDrive::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#0d6efd',
            'is_default' => false,
            'organization_id' => Auth::user()->organization_id ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Drive created successfully!',
            'drive' => $drive
        ]);
    }

    /**
     * Update a drive
     */
    public function update(Request $request, LeadDrive $drive)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('lead_drives')->ignore($drive->id)],
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'is_default' => 'nullable|boolean',
        ]);

        // If setting as default, remove default from others
        if (!empty($validated['is_default'])) {
            LeadDrive::where('is_default', true)->where('id', '!=', $drive->id)->update(['is_default' => false]);
        }

        $drive->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? $drive->description,
            'color' => $validated['color'] ?? $drive->color,
            'is_default' => !empty($validated['is_default']),
        ]);

        return orgRedirect('admin.crm.drives.index')
            ->with('success', 'Drive updated successfully!');
    }

    /**
     * Delete a drive
     */
    public function destroy(LeadDrive $drive)
    {
        // Don't allow deleting the default drive
        if ($drive->is_default) {
            return orgRedirect('admin.crm.drives.index')
                ->with('error', 'Cannot delete the default drive.');
        }

        // Don't allow deleting drives that have leads (scoped to org for non-admin)
        $user = Auth::user();
        $leadsQuery = $drive->leads();
        if (!$user->hasRole('admin') && $user->organization_id) {
            $leadsQuery->where('organization_id', $user->organization_id);
        }

        if ($leadsQuery->count() > 0) {
            return orgRedirect('admin.crm.drives.index')
                ->with('error', 'Cannot delete a drive that contains leads. Please move or delete the leads first.');
        }

        $drive->delete();

        return orgRedirect('admin.crm.drives.index')
            ->with('success', 'Drive deleted successfully!');
    }

    /**
     * Set a drive as default
     */
    public function setDefault(LeadDrive $drive)
    {
        // Remove default from all other drives
        LeadDrive::where('is_default', true)->update(['is_default' => false]);

        // Set this drive as default
        $drive->update(['is_default' => true]);

        return orgRedirect('admin.crm.drives.index')
            ->with('success', 'Default drive updated successfully!');
    }
}
