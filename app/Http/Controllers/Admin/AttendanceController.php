<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance list for the organization.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Attendance::with('user')->orderBy('date', 'desc');

        // Scope to organization
        if ($user->organization_id && !$user->hasRole('admin')) {
            $query->where('organization_id', $user->organization_id);
        } elseif (!$user->hasRole('admin')) {
            $query->whereNull('organization_id');
        }

        // Staff can only see their own attendance
        if ($user->hasRole('staff') && !$user->hasRole('admin') && !$user->hasRole('supervisor')) {
            $query->where('user_id', $user->id);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->where('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('date', '<=', $request->to);
        }

        // Filter by user (for supervisors/admins only)
        if ($request->filled('user_id') && ($user->hasRole('admin') || $user->hasRole('supervisor'))) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->paginate(25);

        // Get org users for the filter dropdown (supervisors/admins only)
        $orgUsers = collect();
        if ($user->hasRole('admin') || $user->hasRole('supervisor')) {
            if ($user->organization_id) {
                $orgUsers = \App\Models\User::where('organization_id', $user->organization_id)->orderBy('name')->get();
            } else {
                $orgUsers = \App\Models\User::orderBy('name')->get();
            }
        }

        // Today's stats (only for supervisors/admins — staff see their own status only)
        $todayStats = $this->getTodayStats($user);

        // Flag so the view knows if user is staff-only (not supervisor/admin)
        $isStaffOnly = $user->hasRole('staff') && !$user->hasRole('admin') && !$user->hasRole('supervisor');

        return view('admin.attendance.index', compact('attendances', 'orgUsers', 'todayStats', 'isStaffOnly'));
    }

    /**
     * Sign in for attendance.
     */
    public function signIn(Request $request)
    {
        $user = Auth::user();

        // Check if already signed in today
        if (Attendance::hasSignedInToday($user->id)) {
            return response()->json(['message' => 'You have already signed in today.', 'already_signed_in' => true], 200);
        }

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'organization_id' => $user->organization_id,
            'date' => today(),
            'signed_in_at' => now(),
            'sign_in_ip' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Signed in successfully at ' . $attendance->signed_in_at->format('h:i A'),
            'attendance' => $attendance,
            'signed_in' => true,
        ]);
    }

    /**
     * Sign out for attendance.
     */
    public function signOut(Request $request)
    {
        $user = Auth::user();
        $attendance = Attendance::todayForUser($user->id);

        if (!$attendance) {
            return response()->json(['message' => 'You have not signed in today.'], 422);
        }

        if ($attendance->signed_out_at) {
            return response()->json(['message' => 'You have already signed out today.', 'already_signed_out' => true], 200);
        }

        $attendance->update([
            'signed_out_at' => now(),
            'sign_out_ip' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Signed out successfully at ' . $attendance->signed_out_at->format('h:i A'),
            'attendance' => $attendance->fresh(),
            'signed_out' => true,
        ]);
    }

    /**
     * Check today's attendance status for the current user.
     */
    public function todayStatus()
    {
        $user = Auth::user();
        $attendance = Attendance::todayForUser($user->id);

        return response()->json([
            'has_signed_in' => $attendance && $attendance->signed_in_at !== null,
            'has_signed_out' => $attendance && $attendance->signed_out_at !== null,
            'signed_in_at' => $attendance?->signed_in_at?->format('h:i A'),
            'signed_out_at' => $attendance?->signed_out_at?->format('h:i A'),
        ]);
    }

    /**
     * Get today's attendance stats for the organization.
     */
    private function getTodayStats($user): array
    {
        $query = Attendance::where('date', today());

        if ($user->organization_id && !$user->hasRole('admin')) {
            $query->where('organization_id', $user->organization_id);
        }

        // Staff can only see their own stats
        if ($user->hasRole('staff') && !$user->hasRole('admin') && !$user->hasRole('supervisor')) {
            $query->where('user_id', $user->id);
        }

        $todayRecords = $query->get();

        return [
            'total_signed_in' => $todayRecords->whereNotNull('signed_in_at')->count(),
            'total_signed_out' => $todayRecords->whereNotNull('signed_out_at')->count(),
            'still_working' => $todayRecords->whereNotNull('signed_in_at')->whereNull('signed_out_at')->count(),
        ];
    }

    /**
     * Get attendance data for the calendar (JSON API).
     */
    public function calendarData(Request $request)
    {
        $user = Auth::user();
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());

        $query = Attendance::with('user')
            ->whereBetween('date', [$start, $end]);

        if ($user->organization_id && !$user->hasRole('admin')) {
            $query->where('organization_id', $user->organization_id);
        }

        // Staff can only see their own attendance on the calendar
        if ($user->hasRole('staff') && !$user->hasRole('admin') && !$user->hasRole('supervisor')) {
            $query->where('user_id', $user->id);
        }

        $attendances = $query->get()->map(function ($a) {
            return [
                'title' => $a->user->name . ' — ' .
                    ($a->signed_in_at ? $a->signed_in_at->format('H:i') : '?') . ' to ' .
                    ($a->signed_out_at ? $a->signed_out_at->format('H:i') : 'ongoing'),
                'start' => $a->date->toDateString(),
                'color' => $a->signed_out_at ? '#10b981' : '#f59e0b',
                'type' => 'attendance',
            ];
        });

        return response()->json($attendances);
    }
}
