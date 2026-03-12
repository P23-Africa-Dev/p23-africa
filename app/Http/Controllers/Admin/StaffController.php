<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffPromotedToSupervisorMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffWelcomeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = User::role(['supervisor', 'staff']);

        // Non-admin org users only see staff in their own organization
        if (!$user->hasRole('admin') && $user->organization_id) {
            $query->where('organization_id', $user->organization_id);
        }

        $staff = $query->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Ensure the current user can access the given staff member based on organization.
     */
    protected function ensureStaffOrgAccess(User $staff)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return;
        }
        if ($user->organization_id && $staff->organization_id !== $user->organization_id) {
            abort(403, 'You are not authorized to manage this staff member.');
        }
    }

    public function create()
    {
        $permissions = \Spatie\Permission\Models\Permission::all();
        $organizations = \App\Models\Organization::orderBy('name')->get();
        $defaultOrg = \App\Models\Organization::where('slug', 'default-org')->first();
        return view('admin.staff.create', compact('permissions', 'organizations', 'defaultOrg'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'nullable|in:staff,supervisor',
            'organization_id' => 'nullable|exists:organizations,id',
            // 'permissions' => 'nullable|array',
        ]);

        $password = Str::random(10);

        // Determine organization_id — non-admin org users forced to their own org
        $orgId = !empty($data['organization_id']) ? $data['organization_id'] : null;
        if (!$currentUser->hasRole('admin') && $currentUser->organization_id) {
            $orgId = $currentUser->organization_id;
        }

        // Determine role — supervisors can only create "staff", not other supervisors
        $assignedRole = $data['role'] ?? 'staff';
        if (!$currentUser->hasRole('admin') && $assignedRole !== 'staff') {
            $assignedRole = 'staff';
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'organization_id' => $orgId,
        ]);

        $role = Role::firstOrCreate(['name' => $assignedRole]);
        $user->assignRole($role);

        // Send staff welcome mail with password (pass org for personalized email)
        // Wrapped in try/catch(\Throwable) to handle SMTP timeouts, FatalErrors, etc.
        $organization = $orgId ? \App\Models\Organization::find($orgId) : null;
        $emailSent = true;
        try {
            Mail::to($user->email)->send(new StaffWelcomeMail($user, $password, $organization));
        } catch (\Throwable $e) {
            $emailSent = false;
            \Illuminate\Support\Facades\Log::error('Failed to send staff welcome email', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'error'   => $e->getMessage(),
            ]);
        }

        // Log activity — also guarded so a logging failure never blocks the redirect
        try {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->withProperties(['role' => $assignedRole, 'email' => $user->email])
                ->log('Created staff account');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to log staff creation activity', [
                'error' => $e->getMessage(),
            ]);
        }

        if ($emailSent) {
            return orgRedirect('admin.staff.index')->with('success', 'Staff created and welcome email sent successfully.');
        }

        return orgRedirect('admin.staff.index')->with('warning', 'Staff created but the welcome email could not be sent. Please check the email configuration.');
    }

    public function edit(User $staff)
    {
        $this->ensureStaffOrgAccess($staff);

        // Get all available permissions
        $permissions = \Spatie\Permission\Models\Permission::all();

        $organizations = \App\Models\Organization::orderBy('name')->get();

        // Ensure Spatie roles and permissions are available
        // $staffPermissions = $staff->getPermissionNames()->toArray();
        // $staffPermissions = $staff->permissions->pluck('name')->toArray();


        return view('admin.staff.edit', compact('staff', 'permissions', 'organizations'));
    }

    public function update(Request $request, User $staff)
    {
        $this->ensureStaffOrgAccess($staff);

        $currentUser = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'organization_id' => 'nullable|exists:organizations,id',
            // 'permissions' => 'nullable|array',
        ]);

        // Determine organization_id — non-admin org users cannot change it
        $orgId = !empty($data['organization_id']) ? $data['organization_id'] : null;
        if (!$currentUser->hasRole('admin') && $currentUser->organization_id) {
            $orgId = $currentUser->organization_id;
        }

        $staff->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'organization_id' => $orgId,
        ]);

        // $staff->syncPermissions($data['permissions'] ?? []);

        activity()->causedBy(Auth::user())->performedOn($staff)->log('Updated staff account');

        return orgRedirect('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(User $staff)
    {
        $this->ensureStaffOrgAccess($staff);

        $staff->delete();

        activity()->causedBy(Auth::user())->performedOn($staff)->log('Deleted staff account');

        return orgRedirect('admin.staff.index')->with('success', 'Staff deleted.');
    }

    public function suspend(User $staff)
    {
        $this->ensureStaffOrgAccess($staff);

        if ($staff->hasRole('admin')) {
            return orgRedirect('admin.staff.index')->with('error', 'Cannot suspend admin accounts.');
        }

        $staff->update(['suspended_at' => now()]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($staff)
            ->withProperties(['action' => 'suspend'])
            ->log('Suspended staff account');
        return orgRedirect('admin.staff.index')->with('success', 'Staff account suspended successfully.');
    }

    public function reactivate(User $staff)
    {
        $this->ensureStaffOrgAccess($staff);

        $staff->update(['suspended_at' => null]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($staff)
            ->withProperties(['action' => 'reactivate'])
            ->log('Reactivated staff account');
        return orgRedirect('admin.staff.index')->with('success', 'Staff account reactivated successfully.');
    }

    public function promote(User $staff)
    {
        $this->ensureStaffOrgAccess($staff);

        // Only admins can promote staff to supervisor
        $currentUser = Auth::user();
        if (!$currentUser->hasRole('admin')) {
            abort(403, 'Only administrators can promote staff to supervisor.');
        }

        // Ensure only staff can be promoted to supervisor
        if ($staff->hasRole('supervisor')) {
            return orgRedirect('admin.staff.index')->with('error', 'User is already a supervisor.');
        }

        if ($staff->hasRole('admin')) {
            return orgRedirect('admin.staff.index')->with('error', 'User is already an admin.');
        }

        $staff->syncRoles('supervisor'); // Promote staff to supervisor

        Mail::to($staff->email)->send(new StaffPromotedToSupervisorMail($staff));

        activity()
            ->causedBy(Auth::user())
            ->performedOn($staff)
            ->withProperties(['promoted_to' => 'supervisor'])
            ->log('Promoted staff to supervisor');
        return orgRedirect('admin.staff.index')->with('success', 'Staff promoted to supervisor successfully.');
    }
}
