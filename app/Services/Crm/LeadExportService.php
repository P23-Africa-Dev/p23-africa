<?php

namespace App\Services\Crm;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadExportService
{
    public function exportColumns(): array
    {
        return [
            'id' => 'ID',
            'full_name' => 'Name',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'company' => 'Company',
            'job_title' => 'Job Title',
            'employee_count' => 'Employee Count',
            'year_founded' => 'Year Founded',
            'industry' => 'Industry',
            'website' => 'Website',
            'company_linkedin_profile' => 'Company LinkedIn Profile',
            'ceo_linkedin_profile' => 'Contact LinkedIn Profile',
            'country' => 'Country',
            'city' => 'City',
            'address' => 'Address',
            'status_label' => 'Status',
            'source_label' => 'Source',
            'priority_label' => 'Priority',
            'estimated_value' => 'Estimated Value',
            'currency' => 'Currency',
            'interested_services' => 'Interested Services',
            'requirements' => 'Requirements',
            'notes' => 'Notes',
            'lost_reason' => 'Lost Reason',
            'drive_name' => 'Pipeline',
            'assigned_user_name' => 'Assigned To',
            'next_follow_up' => 'Next Follow Up',
            'last_contacted_at' => 'Last Contacted',
            'converted_at' => 'Converted At',
            'created_at' => 'Created At',
        ];
    }

    public function buildQuery(Request $request, ?User $user): Builder
    {
        $query = Lead::query()->with(['assignedUser', 'drive']);

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

            $columns = $this->exportColumns();
            fputcsv($file, array_values($columns));

            $query->chunkById(500, function ($leads) use ($file, $columns) {
                foreach ($leads as $lead) {
                    $row = [];
                    foreach (array_keys($columns) as $column) {
                        $row[] = $this->formatExportValue($lead, $column);
                    }

                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function formatExportValue(Lead $lead, string $column): string
    {
        return match ($column) {
            'full_name' => $lead->full_name,
            'status_label' => $lead->status_label,
            'source_label' => $lead->source_label,
            'priority_label' => $lead->priority_label,
            'assigned_user_name' => $lead->assignedUser?->name ?? 'Unassigned',
            'drive_name' => $lead->drive?->name ?? 'Unassigned',
            'interested_services' => is_array($lead->interested_services)
                ? implode(', ', array_filter($lead->interested_services))
                : (string) ($lead->interested_services ?? ''),
            'next_follow_up', 'last_contacted_at', 'converted_at', 'created_at' => $lead->{$column}?->format('Y-m-d H:i') ?? '',
            'estimated_value' => $lead->estimated_value !== null ? (string) $lead->estimated_value : '',
            default => (string) ($lead->{$column} ?? ''),
        };
    }
}