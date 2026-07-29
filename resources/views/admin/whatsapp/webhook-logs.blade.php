<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Webhook Logs</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Inspect incoming Meta webhook events and
                processing outcomes.</p>
        </div>
    </x-slot>

    <div class="py-4">
        @include('admin.whatsapp.partials.nav')

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ orgRoute('admin.whatsapp.webhook-logs') }}"
                    class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All</option>
                            <option value="received" {{ $status === 'received' ? 'selected' : '' }}>Received</option>
                            <option value="processed" {{ $status === 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-8 d-flex gap-2">
                        <button class="btn btn-success" type="submit">Filter</button>
                        <a class="btn btn-outline-secondary"
                            href="{{ orgRoute('admin.whatsapp.webhook-logs') }}">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Account</th>
                                <th>Event Type</th>
                                <th>Signature</th>
                                <th>Status</th>
                                <th>Error</th>
                                <th>Received</th>
                                <th>Headers</th>
                                <th class="pe-3">Payload</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                @php
                                    $statusError = data_get($log->payload, 'entry.0.changes.0.value.statuses.0.errors.0')
                                        ?? data_get($log->payload, 'entry.0.changes.0.value.errors.0');

                                    $metaErrorCode = data_get($statusError, 'code');
                                    $metaErrorTitle = data_get($statusError, 'title');
                                    $metaErrorMessage = data_get($statusError, 'message') ?: data_get($statusError, 'error_data.details');

                                    $resolvedError = $log->error_message
                                        ?: ($metaErrorCode ? ('Code ' . $metaErrorCode . ($metaErrorTitle ? ' - ' . $metaErrorTitle : '')) : null)
                                        ?: $metaErrorMessage;
                                @endphp
                                <tr>
                                    <td class="ps-3">#{{ $log->id }}</td>
                                    <td>{{ $log->account_id ?: '-' }}</td>
                                    <td>{{ $log->event_type ?: '-' }}</td>
                                    <td>
                                        <span class="badge {{ $log->signature_valid ? 'bg-success' : 'bg-danger' }}">
                                            {{ $log->signature_valid ? 'Valid' : 'Invalid' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $log->status === 'processed' ? 'bg-success' : ($log->status === 'failed' ? 'bg-danger' : ($log->status === 'rejected' ? 'bg-dark' : 'bg-secondary')) }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-danger">{{ \Illuminate\Support\Str::limit((string) $resolvedError, 80) }}</small>
                                    </td>
                                    <td>{{ optional($log->created_at)->format('Y-m-d H:i:s') }}</td>
                                    <td style="min-width:240px;">
                                        <details>
                                            <summary class="small">View Headers</summary>
                                            <pre class="small mt-2 mb-0">{{ json_encode($log->headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </details>
                                    </td>
                                    <td class="pe-3" style="min-width:260px;">
                                        <details>
                                            <summary class="small">View JSON</summary>
                                            <pre class="small mt-2 mb-0">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">No webhook logs yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">{{ $logs->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</x-app-layout>
