<?php

namespace App\Services\Crm;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadExportService
{
    public function buildQuery(Request $request, ?User $user): Builder
    {
        $query = Lead::query()->with('assignedUser');

        if ($user && !$user->hasRole('admin')) {
            if ($user->organization_id) {
                $query->where('organization_id', $user->organization_id);
            } else {
                $query->whereNull('organization_id');
            }
        }

        if ($request->filled('drive')) {
            if ($request->drive === 'unassigned') {
                $query->whereNull('drive_id');
            } elseif ($request->drive !== 'all') {
                $query->where('drive_id', $request->drive);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function (Builder $builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('overdue') && $request->overdue === 'true') {
            $query->overdueFollowUp();
        }

        return $query;
    }

    public function streamCsv(Request $request, ?User $user): StreamedResponse
    {
        $query = $this->buildQuery($request, $user)->orderBy('id');

        $filename = 'leads-export-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

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
                'Created At',
            ]);

            $query->chunkById(500, function ($leads) use ($file) {
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
                        $lead->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}