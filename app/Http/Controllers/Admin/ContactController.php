<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactSubmissionsExport;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is handled in routes/web.php
    }

    /**
     * Display a listing of contact submissions.
     */
    public function index(Request $request)
    {
        $query = ContactSubmission::query()->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('country_code', 'like', "%{$search}%")
                    ->orWhere('budget_range', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Filter by services
        if ($request->filled('service')) {
            $service = $request->get('service');
            $query->whereJsonContains('services', $service);
        }

        $contacts = $query->paginate(15)->appends($request->all());

        // Get statistics
        $stats = [
            'total_contacts' => ContactSubmission::count(),
            'today_contacts' => ContactSubmission::whereDate('created_at', today())->count(),
            'this_week_contacts' => ContactSubmission::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month_contacts' => ContactSubmission::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Get unique services for filter dropdown
        $allServices = ContactSubmission::whereNotNull('services')
            ->pluck('services')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('admin.contact.index', compact('contacts', 'stats', 'allServices'));
    }

    /**
     * Display the specified contact submission.
     */
    public function show(ContactSubmission $contact)
    {
        return view('admin.contact.show', compact('contact'));
    }

    /**
     * Remove the specified contact submission from storage.
     * Only admins can delete contact submissions.
     */
    public function destroy(ContactSubmission $contact)
    {
        // Check if user has admin role
        if (!Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can delete contact submissions.'
            ], 403);
        }

        try {
            $contact->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contact submission deleted successfully.'
                ]);
            }

            return redirect()->route('admin.contact.index')
                ->with('success', 'Contact submission deleted successfully.');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete contact submission.'
                ], 500);
            }

            return redirect()->route('admin.contact.index')
                ->with('error', 'Failed to delete contact submission.');
        }
    }

    /**
     * Bulk delete contact submissions.
     * Only admins can perform bulk delete.
     */
    public function bulkDestroy(Request $request)
    {
        // Check if user has admin role
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can delete contact submissions.'
            ], 403);
        }

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:contact_submissions,id'
        ]);

        try {
            $deletedCount = ContactSubmission::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} contact submission(s)."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contact submissions.'
            ], 500);
        }
    }

    /**
     * Export contact submissions to CSV.
     */
    public function export(Request $request)
    {
        $query = ContactSubmission::query()->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('country_code', 'like', "%{$search}%")
                    ->orWhere('budget_range', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        if ($request->filled('service')) {
            $service = $request->get('service');
            $query->whereJsonContains('services', $service);
        }

        $contacts = $query->get();

        $filename = 'contact_submissions_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Company Name',
                'Email',
                'Phone',
                'Country Code',
                'Budget Range',
                'Services',
                'Message',
                'IP Address',
                'User Agent',
                'Email Sent At',
                'Submitted At'
            ]);

            // Add data rows
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->company_name,
                    $contact->email,
                    $contact->phone,
                    $contact->country_code,
                    $contact->budget_range ? '$' . number_format($contact->budget_range) : '',
                    is_array($contact->services) ? implode(', ', $contact->services) : $contact->services,
                    $contact->message,
                    $contact->ip_address,
                    $contact->user_agent,
                    $contact->email_sent_at?->format('Y-m-d H:i:s'),
                    $contact->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export contact submissions to XLSX.
     */
    public function exportXlsx(Request $request)
    {
        $query = ContactSubmission::query()->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('country_code', 'like', "%{$search}%")
                    ->orWhere('budget_range', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        if ($request->filled('service')) {
            $service = $request->get('service');
            $query->whereJsonContains('services', $service);
        }

        $filename = 'contact_submissions_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ContactSubmissionsExport($query), $filename);
    }
}
