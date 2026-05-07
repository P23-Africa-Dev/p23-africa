<?php

namespace App\Services\Crm;

use App\Models\Lead;
use App\Models\LeadDrive;
use App\Models\LeadStatus;
use App\Models\Organization;
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

        if ($request->has('organization_id')) {
            $organizationId = $request->input('organization_id');

            if ($organizationId === null || $organizationId === '') {
                $query->whereNull('organization_id');
            } else {
                $query->where('organization_id', (int) $organizationId);
            }
        } elseif ($user) {
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

    public function streamMigrationJson(Request $request, ?User $user): StreamedResponse
    {
        $targetOrganizationId = $request->input('organization_id');
        $organization = is_null($targetOrganizationId)
            ? null
            : Organization::query()->find((int) $targetOrganizationId);

        $statusCollection = LeadStatus::forOrganization($targetOrganizationId)
            ->orderBy('position')
            ->get();

        $statusMap = $statusCollection
            ->keyBy('slug')
            ->map(function (LeadStatus $status) {
                return [
                    'id' => $status->id,
                    'name' => $status->name,
                    'slug' => $status->slug,
                    'color' => $status->color,
                    'position' => $status->position,
                    'is_default' => (bool) $status->is_default,
                    'is_won' => (bool) $status->is_won,
                    'is_lost' => (bool) $status->is_lost,
                ];
            })
            ->toArray();

        $drives = LeadDrive::query()
            ->when(
                is_null($targetOrganizationId),
                fn(Builder $query) => $query->whereNull('organization_id'),
                fn(Builder $query) => $query->where('organization_id', $targetOrganizationId)
            )
            ->orderBy('name')
            ->get()
            ->map(fn(LeadDrive $drive) => [
                'id' => $drive->id,
                'organization_id' => $drive->organization_id,
                'name' => $drive->name,
                'slug' => $drive->slug,
                'description' => $drive->description,
                'color' => $drive->color,
                'is_default' => (bool) $drive->is_default,
                'created_at' => $drive->created_at?->toIso8601String(),
                'updated_at' => $drive->updated_at?->toIso8601String(),
            ])
            ->values()
            ->all();

        $users = User::query()
            ->when(
                is_null($targetOrganizationId),
                fn(Builder $query) => $query->whereNull('organization_id'),
                fn(Builder $query) => $query->where('organization_id', $targetOrganizationId)
            )
            ->with('roles')
            ->orderBy('id')
            ->get()
            ->map(fn(User $exportUser) => [
                'id' => $exportUser->id,
                'organization_id' => $exportUser->organization_id,
                'name' => $exportUser->name,
                'email' => $exportUser->email,
                'roles' => $exportUser->roles->pluck('name')->values()->all(),
                'created_at' => $exportUser->created_at?->toIso8601String(),
                'updated_at' => $exportUser->updated_at?->toIso8601String(),
            ])
            ->values()
            ->all();

        $query = $this->buildQuery($request, $user)
            ->with([
                'drive',
                'assignedUser.roles',
                'creator.roles',
                'activities.user.roles',
                'emails.user.roles',
            ])
            ->orderBy('id');

        $leadCount = (clone $query)->count();
        $activityCount = (clone $query)
            ->join('lead_activities', 'lead_activities.lead_id', '=', 'leads.id')
            ->count('lead_activities.id');
        $emailCount = (clone $query)
            ->join('lead_emails', 'lead_emails.lead_id', '=', 'leads.id')
            ->count('lead_emails.id');

        $filename = 'leads-migration-' . ($organization?->slug ?? 'global') . '-' . now()->format('Y-m-d-His') . '.json';
        $headers = [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($query, $organization, $targetOrganizationId, $statusCollection, $statusMap, $drives, $users, $leadCount, $activityCount, $emailCount) {
            $envelope = [
                'schema_version' => 'crm-leads-migration.v1',
                'exported_at' => now()->toIso8601String(),
                'organization' => [
                    'id' => $organization?->id,
                    'name' => $organization?->name,
                    'slug' => $organization?->slug,
                    'crm_currency' => $organization?->getCrmCurrency(),
                ],
                'metadata' => [
                    'lead_count' => $leadCount,
                    'activity_count' => $activityCount,
                    'email_count' => $emailCount,
                    'status_count' => $statusCollection->count(),
                    'drive_count' => count($drives),
                    'user_count' => count($users),
                    'filters' => [
                        'organization_id' => $targetOrganizationId,
                    ],
                ],
                'mappings' => [
                    'statuses' => array_values($statusMap),
                    'drives' => $drives,
                    'users' => $users,
                ],
            ];

            $encodedEnvelope = json_encode($envelope, JSON_UNESCAPED_SLASHES);
            if (!is_string($encodedEnvelope) || $encodedEnvelope === '' || $encodedEnvelope === '{}') {
                $encodedEnvelope = '{"schema_version":"crm-leads-migration.v1"}';
            }

            echo substr($encodedEnvelope, 0, -1);
            echo ',"leads":[';

            $isFirstLead = true;
            $query->chunkById(200, function ($leads) use (&$isFirstLead, $statusMap) {
                foreach ($leads as $lead) {
                    if (!$isFirstLead) {
                        echo ',';
                    }

                    echo json_encode($this->mapLeadForMigration($lead, $statusMap), JSON_UNESCAPED_SLASHES);
                    $isFirstLead = false;
                }
            });

            echo ']}';
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

    protected function mapLeadForMigration(Lead $lead, array $statusMap): array
    {
        $leadAttributes = [
            'id' => $lead->id,
            'organization_id' => $lead->organization_id,
            'drive_id' => $lead->drive_id,
            'created_by' => $lead->created_by,
            'assigned_to' => $lead->assigned_to,
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'full_name' => $lead->full_name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'company' => $lead->company,
            'employee_count' => $lead->employee_count,
            'year_founded' => $lead->year_founded,
            'industry' => $lead->industry,
            'job_title' => $lead->job_title,
            'website' => $lead->website,
            'company_linkedin_profile' => $lead->company_linkedin_profile,
            'ceo_linkedin_profile' => $lead->ceo_linkedin_profile,
            'country' => $lead->country,
            'city' => $lead->city,
            'address' => $lead->address,
            'status' => $lead->status,
            'source' => $lead->source,
            'priority' => $lead->priority,
            'estimated_value' => $lead->estimated_value,
            'currency' => $lead->currency,
            'interested_services' => $lead->interested_services,
            'requirements' => $lead->requirements,
            'notes' => $lead->notes,
            'last_contacted_at' => $lead->last_contacted_at?->toIso8601String(),
            'next_follow_up' => $lead->next_follow_up?->toIso8601String(),
            'converted_at' => $lead->converted_at?->toIso8601String(),
            'lost_reason' => $lead->lost_reason,
            'source_type' => $lead->source_type,
            'source_id' => $lead->source_id,
            'created_at' => $lead->created_at?->toIso8601String(),
            'updated_at' => $lead->updated_at?->toIso8601String(),
            'deleted_at' => $lead->deleted_at?->toIso8601String(),
        ];

        $activities = $lead->activities
            ->sortBy('id')
            ->values()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'lead_id' => $activity->lead_id,
                    'user_id' => $activity->user_id,
                    'type' => $activity->type,
                    'title' => $activity->title,
                    'description' => $activity->description,
                    'old_value' => $activity->old_value,
                    'new_value' => $activity->new_value,
                    'scheduled_at' => $activity->scheduled_at?->toIso8601String(),
                    'completed_at' => $activity->completed_at?->toIso8601String(),
                    'is_completed' => (bool) $activity->is_completed,
                    'metadata' => $activity->metadata,
                    'created_at' => $activity->created_at?->toIso8601String(),
                    'updated_at' => $activity->updated_at?->toIso8601String(),
                    'user' => $activity->user ? [
                        'id' => $activity->user->id,
                        'organization_id' => $activity->user->organization_id,
                        'name' => $activity->user->name,
                        'email' => $activity->user->email,
                        'roles' => $activity->user->roles?->pluck('name')->values()->all() ?? [],
                    ] : null,
                ];
            })
            ->all();

        $emails = $lead->emails
            ->sortBy('id')
            ->values()
            ->map(function ($email) {
                return [
                    'id' => $email->id,
                    'lead_id' => $email->lead_id,
                    'user_id' => $email->user_id,
                    'from_email' => $email->from_email,
                    'from_name' => $email->from_name,
                    'to_email' => $email->to_email,
                    'to_name' => $email->to_name,
                    'subject' => $email->subject,
                    'body' => $email->body,
                    'body_html' => $email->body_html,
                    'direction' => $email->direction,
                    'message_id' => $email->message_id,
                    'in_reply_to' => $email->in_reply_to,
                    'thread_id' => $email->thread_id,
                    'status' => $email->status,
                    'sent_at' => $email->sent_at?->toIso8601String(),
                    'read_at' => $email->read_at?->toIso8601String(),
                    'attachments' => $email->attachments,
                    'metadata' => $email->metadata,
                    'created_at' => $email->created_at?->toIso8601String(),
                    'updated_at' => $email->updated_at?->toIso8601String(),
                    'user' => $email->user ? [
                        'id' => $email->user->id,
                        'organization_id' => $email->user->organization_id,
                        'name' => $email->user->name,
                        'email' => $email->user->email,
                        'roles' => $email->user->roles?->pluck('name')->values()->all() ?? [],
                    ] : null,
                ];
            })
            ->all();

        return [
            'lead' => $leadAttributes,
            'pipeline' => $lead->drive ? [
                'id' => $lead->drive->id,
                'name' => $lead->drive->name,
                'slug' => $lead->drive->slug,
                'description' => $lead->drive->description,
                'color' => $lead->drive->color,
                'is_default' => (bool) $lead->drive->is_default,
            ] : null,
            'status' => $statusMap[$lead->status] ?? [
                'id' => null,
                'name' => $lead->status_label,
                'slug' => $lead->status,
                'color' => $lead->status_color,
                'position' => null,
                'is_default' => false,
                'is_won' => false,
                'is_lost' => false,
            ],
            'assigned_user' => $lead->assignedUser ? [
                'id' => $lead->assignedUser->id,
                'organization_id' => $lead->assignedUser->organization_id,
                'name' => $lead->assignedUser->name,
                'email' => $lead->assignedUser->email,
                'roles' => $lead->assignedUser->roles?->pluck('name')->values()->all() ?? [],
            ] : null,
            'created_by_user' => $lead->creator ? [
                'id' => $lead->creator->id,
                'organization_id' => $lead->creator->organization_id,
                'name' => $lead->creator->name,
                'email' => $lead->creator->email,
                'roles' => $lead->creator->roles?->pluck('name')->values()->all() ?? [],
            ] : null,
            'activities' => $activities,
            'status_history' => array_values(array_filter($activities, fn(array $activity) => $activity['type'] === 'status_change')),
            'tasks_reminders' => array_values(array_filter($activities, fn(array $activity) => in_array($activity['type'], ['task', 'follow_up', 'meeting', 'call'], true))),
            'emails' => $emails,
            // Tag support placeholder for future CRM extensions.
            'tags' => [],
        ];
    }
}
