<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffPromotedToAdminMail;
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
        $staff = User::role('staff')->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('admin.staff.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            // 'permissions' => 'nullable|array',
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
        ]);


        // Ensure staff role exists
        $role = Role::firstOrCreate(['name' => 'staff']);
        $user->assignRole($role);

        // $user->syncPermissions($data['permissions'] ?? []);

        // Send staff welcome mail with password
        // $permissions = $user->getPermissionNames();
        Mail::to($user->email)->send(new StaffWelcomeMail($user, $password));

        // activity()->causedBy(Auth::user())->performedOn($user)->log('Created staff account');

        return redirect()->route('admin.staff.index')->with('success', 'Staff created and notified.');
    }

    public function edit(User $staff)
    {
        // Get all available permissions
        $permissions = \Spatie\Permission\Models\Permission::all();

        // Ensure Spatie roles and permissions are available
        // $staffPermissions = $staff->getPermissionNames()->toArray();
        // $staffPermissions = $staff->permissions->pluck('name')->toArray();


        return view('admin.staff.edit', compact('staff', 'permissions'));
    }

    public function update(Request $request, User $staff)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            // 'permissions' => 'nullable|array',
        ]);

        $staff->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // $staff->syncPermissions($data['permissions'] ?? []);

        activity()->causedBy(Auth::user())->performedOn($staff)->log('Updated staff account');

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();

        activity()->causedBy(Auth::user())->performedOn($staff)->log('Deleted staff account');

        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted.');
    }

    public function suspend(User $staff)
    {
        if ($staff->hasRole('admin')) {
            return redirect()->back()->with('error', 'Cannot suspend admin accounts.');
        }

        $staff->update(['suspended_at' => now()]);

        // activity()->causedBy(Auth::user())->performedOn($staff)->log('Suspended staff account');

        return redirect()->back()->with('success', 'Staff account suspended successfully.');
    }

    public function reactivate(User $staff)
    {
        $staff->update(['suspended_at' => null]);

        // activity()->causedBy(Auth::user())->performedOn($staff)->log('Reactivated staff account');

        return redirect()->back()->with('success', 'Staff account reactivated successfully.');
    }

    public function promote(User $staff)
    {
        // Ensure only staff can be promoted
        if ($staff->hasRole('admin')) {
            return back()->with('error', 'User is already an admin.');
        }

        $staff->syncRoles('admin'); // Replace 'staff' with 'admin'

        Mail::to($staff->email)->send(new StaffPromotedToAdminMail($staff));

        // activity()
        //     ->causedBy(Auth::user())
        //     ->performedOn($staff)
        //     ->log('Promoted staff to admin');

        return redirect()->back()->with('success', 'Staff promoted to admin successfully.');
    }
}
