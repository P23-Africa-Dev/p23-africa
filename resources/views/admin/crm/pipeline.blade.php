<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Lead Pipeline') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Drag and drop leads to update their status</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.crm.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-list me-2"></i>List View
                </a>
                <a href="{{ route('admin.crm.import') }}" class="btn btn-outline-success">
                    <i class="fa fa-file-import me-2"></i>Import Leads
                </a>
                <a href="{{ route('admin.crm.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>Add New Lead
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .pipeline-container {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 20px;
            min-height: calc(100vh - 250px);
        }

        .pipeline-column {
            min-width: 260px;
            max-width: 280px;
            flex-shrink: 0;
        }

        .pipeline-header {
            padding: 12px 16px;
            border-radius: 10px 10px 0 0;
            color: white;
            font-weight: 600;
        }

        /* Pipeline header colors - must match Lead model getStatusColorAttribute */
        .pipeline-header.new {
            background: #0dcaf0;
            /* info */
        }

        .pipeline-header.contacted {
            background: #0d6efd;
            /* primary */
        }

        .pipeline-header.qualified {
            background: #ffc107;
            /* warning */
            color: #212529;
        }

        .pipeline-header.unqualified {
            background: #6c757d;
            /* secondary */
        }

        .pipeline-header.proposal {
            background: #6c757d;
            /* secondary */
        }

        .pipeline-header.negotiation {
            background: #212529;
            /* dark */
        }

        .pipeline-header.won {
            background: #198754;
            /* success */
        }

        .pipeline-header.lost {
            background: #dc3545;
            /* danger */
        }

        .pipeline-body {
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
            padding: 12px;
            min-height: 400px;
            transition: background-color 0.2s;
        }

        .dark .pipeline-body {
            background: #374151;
        }

        .pipeline-body.sortable-drag-over {
            background: #e3f2fd;
        }

        .dark .pipeline-body.sortable-drag-over {
            background: #4b5563;
        }

        .lead-card {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            cursor: grab;
            text-decoration: none !important;
            display: block;
        }

        .lead-card:active {
            cursor: grabbing;
        }

        .dark .lead-card {
            background: #1f2937;
        }

        .lead-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .lead-card.sortable-ghost {
            opacity: 0.4;
            background: #e3f2fd;
        }

        .lead-card.sortable-chosen {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transform: rotate(3deg);
        }

        .lead-card .lead-name {
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
        }

        .dark .lead-card .lead-name {
            color: #f3f4f6;
        }

        .lead-card .lead-company {
            color: #007bff;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .lead-card .lead-value {
            color: #28a745;
            font-weight: 600;
        }

        .lead-card .lead-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .pipeline-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .lead-card-link {
            position: absolute;
            top: 8px;
            right: 8px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .lead-card:hover .lead-card-link {
            opacity: 1;
        }

        .lead-card {
            position: relative;
        }
    </style>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="successToast" class="toast" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fa fa-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="successMessage"></div>
        </div>
        <div id="errorToast" class="toast" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fa fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="errorMessage"></div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <!-- Pipeline Summary -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fa fa-users text-primary fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total in Pipeline</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        {{ collect($pipeline)->sum('count') }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-dollar-sign text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Total Pipeline Value</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        ${{ number_format(collect($pipeline)->sum('value')) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3">
                                    <i class="fa fa-trophy text-success fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Won Value</h6>
                                    <h3 class="mb-0 text-dark dark:text-gray-100">
                                        ${{ number_format($pipeline['won']['value'] ?? 0) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3">
                                    <i class="fa fa-percentage text-info fa-2x"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="text-muted dark:text-gray-400 mb-1">Win Rate</h6>
                                    @php
                                        $total = ($pipeline['won']['count'] ?? 0) + ($pipeline['lost']['count'] ?? 0);
                                        $winRate =
                                            $total > 0 ? round((($pipeline['won']['count'] ?? 0) / $total) * 100) : 0;
                                    @endphp
                                    <h3 class="mb-0 text-dark dark:text-gray-100">{{ $winRate }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pipeline Board -->
            <div class="pipeline-container">
                @foreach ($statuses as $statusKey => $statusLabel)
                    @php
                        $columnData = $pipeline[$statusKey] ?? ['leads' => collect(), 'count' => 0, 'value' => 0];
                    @endphp
                    <div class="pipeline-column">
                        <div class="pipeline-header {{ $statusKey }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $statusLabel }}</span>
                                <span class="badge bg-white text-dark"
                                    id="count-{{ $statusKey }}">{{ $columnData['count'] }}</span>
                            </div>
                            <div class="pipeline-stats">
                                <span id="value-{{ $statusKey }}">${{ number_format($columnData['value']) }}</span>
                            </div>
                        </div>
                        <div class="pipeline-body sortable-column" data-status="{{ $statusKey }}">
                            @forelse($columnData['leads'] as $lead)
                                <div class="lead-card" data-id="{{ $lead->id }}"
                                    data-value="{{ $lead->estimated_value ?? 0 }}">
                                    <a href="{{ route('admin.crm.show', $lead) }}" class="lead-card-link text-primary"
                                        title="View Details" onclick="event.stopPropagation();">
                                        <i class="fa fa-external-link-alt"></i>
                                    </a>
                                    <div class="lead-name">{{ $lead->full_name }}</div>
                                    @if ($lead->company)
                                        <div class="lead-company">{{ $lead->company }}</div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if ($lead->estimated_value)
                                            <span
                                                class="lead-value">${{ number_format($lead->estimated_value) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                        <span
                                            class="badge bg-{{ $lead->priority_color }}">{{ $lead->priority_label }}</span>
                                    </div>
                                    <div class="lead-meta mt-2">
                                        <div class="d-flex justify-content-between">
                                            @if ($lead->assignedUser)
                                                <span><i
                                                        class="fa fa-user me-1"></i>{{ $lead->assignedUser->name }}</span>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                            <span>{{ $lead->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if ($lead->next_follow_up)
                                            <div class="mt-1 {{ $lead->isFollowUpOverdue() ? 'text-danger' : '' }}">
                                                <i class="fa fa-clock me-1"></i>
                                                {{ $lead->next_follow_up->format('M d') }}
                                                @if ($lead->isFollowUpOverdue())
                                                    <span class="text-danger">(Overdue)</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4 empty-placeholder">
                                    <i class="fa fa-inbox mb-2"></i>
                                    <p class="mb-0 small">No leads</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Lost Reason Modal -->
    <div class="modal fade" id="lostReasonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <div class="modal-header">
                    <h5 class="modal-title dark:text-gray-100">Reason for Lost</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted dark:text-gray-400">Please provide a reason for marking this lead as lost:</p>
                    <textarea id="lostReasonText" class="form-control dark:bg-gray-700 dark:text-gray-100" rows="3"
                        placeholder="Enter reason..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmLostBtn">Mark as Lost</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- SortableJS CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const columns = document.querySelectorAll('.sortable-column');
                const csrfToken = '{{ csrf_token() }}';
                let pendingLostLead = null;
                let pendingLostSourceColumn = null;

                // Initialize SortableJS for each column
                columns.forEach(column => {
                    new Sortable(column, {
                        group: 'leads',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        filter: '.empty-placeholder',
                        onStart: function(evt) {
                            // Hide empty placeholders when dragging starts
                            document.querySelectorAll('.empty-placeholder').forEach(el => el.style
                                .display = 'none');
                        },
                        onEnd: function(evt) {
                            const leadId = evt.item.dataset.id;
                            const newStatus = evt.to.dataset.status;
                            const oldStatus = evt.from.dataset.status;

                            // Show empty placeholders if columns are empty
                            updateEmptyPlaceholders();

                            if (newStatus === oldStatus) return;

                            // If moving to "lost", show reason modal
                            if (newStatus === 'lost') {
                                pendingLostLead = {
                                    id: leadId,
                                    element: evt.item
                                };
                                pendingLostSourceColumn = evt.from;
                                const modal = new bootstrap.Modal(document.getElementById(
                                    'lostReasonModal'));
                                modal.show();
                                return;
                            }

                            // Update status via AJAX
                            updateLeadStatus(leadId, newStatus, null, evt.item, evt.from);
                        }
                    });
                });

                // Confirm lost button handler
                document.getElementById('confirmLostBtn').addEventListener('click', function() {
                    const reason = document.getElementById('lostReasonText').value.trim();
                    if (!reason) {
                        alert('Please provide a reason');
                        return;
                    }

                    updateLeadStatus(pendingLostLead.id, 'lost', reason, pendingLostLead.element,
                        pendingLostSourceColumn);
                    bootstrap.Modal.getInstance(document.getElementById('lostReasonModal')).hide();
                    document.getElementById('lostReasonText').value = '';
                });

                // Cancel lost modal - move card back
                document.getElementById('lostReasonModal').addEventListener('hidden.bs.modal', function() {
                    if (pendingLostLead && pendingLostSourceColumn) {
                        // Check if the lead is still in the lost column
                        const lostColumn = document.querySelector('.sortable-column[data-status="lost"]');
                        if (lostColumn.contains(pendingLostLead.element)) {
                            pendingLostSourceColumn.appendChild(pendingLostLead.element);
                            updateEmptyPlaceholders();
                            updateColumnCounts();
                        }
                    }
                    pendingLostLead = null;
                    pendingLostSourceColumn = null;
                });

                function updateLeadStatus(leadId, status, lostReason, element, sourceColumn) {
                    fetch(`/admin/crm/${leadId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: status,
                                lost_reason: lostReason
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message || 'Status updated successfully!');
                                updateColumnCounts();
                                pendingLostLead = null;
                                pendingLostSourceColumn = null;
                            } else {
                                showToast('error', data.message || 'Failed to update status');
                                // Move back to source column
                                if (sourceColumn && element) {
                                    sourceColumn.appendChild(element);
                                    updateEmptyPlaceholders();
                                    updateColumnCounts();
                                }
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            showToast('error', 'An error occurred');
                            // Move back to source column
                            if (sourceColumn && element) {
                                sourceColumn.appendChild(element);
                                updateEmptyPlaceholders();
                                updateColumnCounts();
                            }
                        });
                }

                function updateEmptyPlaceholders() {
                    columns.forEach(column => {
                        const cards = column.querySelectorAll('.lead-card');
                        let placeholder = column.querySelector('.empty-placeholder');

                        if (cards.length === 0) {
                            if (!placeholder) {
                                placeholder = document.createElement('div');
                                placeholder.className = 'text-center text-muted py-4 empty-placeholder';
                                placeholder.innerHTML =
                                    '<i class="fa fa-inbox mb-2"></i><p class="mb-0 small">No leads</p>';
                                column.appendChild(placeholder);
                            }
                            placeholder.style.display = 'block';
                        } else if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    });
                }

                function updateColumnCounts() {
                    columns.forEach(column => {
                        const status = column.dataset.status;
                        const cards = column.querySelectorAll('.lead-card');
                        const countEl = document.getElementById(`count-${status}`);
                        const valueEl = document.getElementById(`value-${status}`);

                        if (countEl) {
                            countEl.textContent = cards.length;
                        }

                        if (valueEl) {
                            let total = 0;
                            cards.forEach(card => {
                                total += parseFloat(card.dataset.value) || 0;
                            });
                            valueEl.textContent = '$' + total.toLocaleString();
                        }
                    });
                }

                function showToast(type, message) {
                    const toast = document.getElementById(type + 'Toast');
                    const msgEl = document.getElementById(type + 'Message');
                    if (toast && msgEl) {
                        msgEl.textContent = message;
                        new bootstrap.Toast(toast).show();
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
