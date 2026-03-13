<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Lead Statuses') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Customize the statuses used in your CRM pipeline
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Leads
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStatusModal">
                    <i class="fa fa-plus me-2"></i>Add Status
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Info Card --}}
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle text-info me-3 fs-5"></i>
                        <span class="text-muted dark:text-gray-400">
                            Drag and drop statuses to reorder them. The order determines how they appear in the pipeline
                            view. Statuses marked as <strong>Won</strong> or <strong>Lost</strong> are treated as
                            terminal stages.
                        </span>
                    </div>
                </div>
            </div>

            {{-- Statuses Table --}}
            <div class="card border-0 shadow-sm dark:bg-gray-800">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light dark:bg-gray-700">
                                <tr>
                                    <th style="width: 40px;" class="ps-3"></th>
                                    <th class="dark:text-gray-300">Status</th>
                                    <th class="dark:text-gray-300">Color</th>
                                    <th class="dark:text-gray-300 text-center">Default</th>
                                    <th class="dark:text-gray-300 text-center">Won</th>
                                    <th class="dark:text-gray-300 text-center">Lost</th>
                                    <th class="dark:text-gray-300 text-center">Leads</th>
                                    <th class="dark:text-gray-300 text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="statusSortable">
                                @forelse ($statuses as $status)
                                    <tr data-id="{{ $status->id }}" class="status-row" style="cursor: grab;">
                                        <td class="ps-3 text-muted">
                                            <i class="fa fa-grip-vertical drag-handle"></i>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge me-2"
                                                    style="background-color: {{ $status->color }}; min-width: 80px;">
                                                    {{ $status->name }}
                                                </span>
                                                <span
                                                    class="text-muted dark:text-gray-400 small">{{ $status->slug }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="d-inline-block rounded-circle"
                                                style="width: 20px; height: 20px; background-color: {{ $status->color }};"></span>
                                        </td>
                                        <td class="text-center">
                                            @if ($status->is_default)
                                                <i class="fa fa-check-circle text-primary"></i>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($status->is_won)
                                                <i class="fa fa-trophy text-success"></i>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($status->is_lost)
                                                <i class="fa fa-times-circle text-danger"></i>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $status->leads_count ?? 0 }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-status"
                                                data-id="{{ $status->id }}" data-name="{{ $status->name }}"
                                                data-color="{{ $status->color }}"
                                                data-is-default="{{ $status->is_default ? '1' : '0' }}"
                                                data-is-won="{{ $status->is_won ? '1' : '0' }}"
                                                data-is-lost="{{ $status->is_lost ? '1' : '0' }}">
                                                <i class="fa fa-edit me-1"></i>Edit
                                            </button>
                                            @if (!$status->is_default)
                                                <form action="{{ orgRoute('admin.crm.statuses.destroy', $status) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this status?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa fa-trash me-1"></i>Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fa fa-tags fa-3x text-muted mb-3 d-block"></i>
                                            <h5 class="text-muted">No statuses configured</h5>
                                            <p class="text-muted">Create your first lead status to customize your
                                                pipeline.</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#createStatusModal">
                                                <i class="fa fa-plus me-2"></i>Add Status
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Status Modal --}}
    <div class="modal fade" id="createStatusModal" tabindex="-1" aria-labelledby="createStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <form action="{{ orgRoute('admin.crm.statuses.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title dark:text-gray-100" id="createStatusModalLabel">
                            <i class="fa fa-plus-circle me-2 text-primary"></i>Add New Status
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="createName" class="form-label dark:text-gray-100">Status Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control dark:bg-gray-700 dark:text-gray-100"
                                id="createName" name="name" required placeholder="e.g., Follow Up, Interested">
                        </div>
                        <div class="mb-3">
                            <label class="form-label dark:text-gray-100">Color <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex gap-2 flex-wrap" id="createColorPickers">
                                @php
                                    $colors = [
                                        '#0dcaf0' => 'Info',
                                        '#0d6efd' => 'Primary',
                                        '#ffc107' => 'Warning',
                                        '#6c757d' => 'Secondary',
                                        '#343a40' => 'Dark',
                                        '#198754' => 'Success',
                                        '#dc3545' => 'Danger',
                                        '#6f42c1' => 'Purple',
                                        '#fd7e14' => 'Orange',
                                        '#20c997' => 'Teal',
                                        '#d63384' => 'Pink',
                                        '#0a58ca' => 'Blue',
                                    ];
                                @endphp
                                @foreach ($colors as $hex => $label)
                                    <label class="color-preset" style="cursor: pointer;"
                                        title="{{ $label }}">
                                        <input type="radio" name="color" value="{{ $hex }}"
                                            class="d-none" {{ $loop->first ? 'checked' : '' }}>
                                        <span class="d-inline-block rounded"
                                            style="width: 32px; height: 32px; background-color: {{ $hex }}; border: 2px solid {{ $loop->first ? '#212529' : 'transparent' }};"
                                            onclick="selectColor(this, 'createColorPickers')"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="createIsDefault"
                                        name="is_default" value="1">
                                    <label class="form-check-label dark:text-gray-100" for="createIsDefault">
                                        <i class="fa fa-star text-warning me-1"></i>Default
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input create-terminal" type="checkbox" id="createIsWon"
                                        name="is_won" value="1">
                                    <label class="form-check-label dark:text-gray-100" for="createIsWon">
                                        <i class="fa fa-trophy text-success me-1"></i>Won
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input create-terminal" type="checkbox" id="createIsLost"
                                        name="is_lost" value="1">
                                    <label class="form-check-label dark:text-gray-100" for="createIsLost">
                                        <i class="fa fa-times-circle text-danger me-1"></i>Lost
                                    </label>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted dark:text-gray-400">
                            <strong>Default:</strong> New leads get this status. <strong>Won/Lost:</strong> Terminal
                            stages. A lead cannot be both.
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i>Add Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Status Modal --}}
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <form id="editStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title dark:text-gray-100" id="editStatusModalLabel">
                            <i class="fa fa-edit me-2 text-primary"></i>Edit Status
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editName" class="form-label dark:text-gray-100">Status Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control dark:bg-gray-700 dark:text-gray-100"
                                id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label dark:text-gray-100">Color <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex gap-2 flex-wrap" id="editColorPickers">
                                @foreach ($colors as $hex => $label)
                                    <label class="color-preset" style="cursor: pointer;"
                                        title="{{ $label }}">
                                        <input type="radio" name="color" value="{{ $hex }}"
                                            class="d-none edit-color-radio">
                                        <span class="d-inline-block rounded edit-color-span"
                                            data-color="{{ $hex }}"
                                            style="width: 32px; height: 32px; background-color: {{ $hex }}; border: 2px solid transparent;"
                                            onclick="selectColor(this, 'editColorPickers')"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="editIsDefault"
                                        name="is_default" value="1">
                                    <label class="form-check-label dark:text-gray-100" for="editIsDefault">
                                        <i class="fa fa-star text-warning me-1"></i>Default
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input edit-terminal" type="checkbox" id="editIsWon"
                                        name="is_won" value="1">
                                    <label class="form-check-label dark:text-gray-100" for="editIsWon">
                                        <i class="fa fa-trophy text-success me-1"></i>Won
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input edit-terminal" type="checkbox" id="editIsLost"
                                        name="is_lost" value="1">
                                    <label class="form-check-label dark:text-gray-100" for="editIsLost">
                                        <i class="fa fa-times-circle text-danger me-1"></i>Lost
                                    </label>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted dark:text-gray-400">
                            <strong>Default:</strong> New leads get this status. <strong>Won/Lost:</strong> Terminal
                            stages — a lead cannot be both.
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .color-preset span {
                transition: border-color 0.2s, transform 0.15s;
            }

            .color-preset span:hover {
                transform: scale(1.1);
            }

            .status-row {
                transition: background-color 0.15s;
            }

            .status-row.sortable-ghost {
                opacity: 0.4;
                background-color: #e9ecef;
            }

            .drag-handle {
                cursor: grab;
            }

            .drag-handle:active {
                cursor: grabbing;
            }
        </style>
    @endpush

    @push('scripts')
        {{-- SortableJS CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            // Color picker helper
            function selectColor(el, containerId) {
                const container = document.getElementById(containerId);
                container.querySelectorAll('.color-preset span').forEach(s => s.style.borderColor = 'transparent');
                el.style.borderColor = '#212529';
                el.closest('label').querySelector('input[type="radio"]').checked = true;
            }

            document.addEventListener('DOMContentLoaded', function() {
                // ─── Won / Lost mutual exclusion (Create) ───
                const createWon = document.getElementById('createIsWon');
                const createLost = document.getElementById('createIsLost');
                if (createWon && createLost) {
                    createWon.addEventListener('change', function() {
                        if (this.checked) createLost.checked = false;
                    });
                    createLost.addEventListener('change', function() {
                        if (this.checked) createWon.checked = false;
                    });
                }

                // ─── Won / Lost mutual exclusion (Edit) ───
                const editWon = document.getElementById('editIsWon');
                const editLost = document.getElementById('editIsLost');
                if (editWon && editLost) {
                    editWon.addEventListener('change', function() {
                        if (this.checked) editLost.checked = false;
                    });
                    editLost.addEventListener('change', function() {
                        if (this.checked) editWon.checked = false;
                    });
                }

                // ─── Edit Button Handler ───
                document.querySelectorAll('.edit-status').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const color = this.dataset.color;
                        const isDefault = this.dataset.isDefault === '1';
                        const isWon = this.dataset.isWon === '1';
                        const isLost = this.dataset.isLost === '1';

                        // Update form action
                        const form = document.getElementById('editStatusForm');
                        form.action = `${window.adminBasePath}/crm/statuses/${id}`;

                        // Fill fields
                        document.getElementById('editName').value = name;
                        document.getElementById('editIsDefault').checked = isDefault;
                        document.getElementById('editIsWon').checked = isWon;
                        document.getElementById('editIsLost').checked = isLost;

                        // Select color
                        const colorNormalized = color.toLowerCase();
                        document.querySelectorAll('#editColorPickers .edit-color-radio').forEach(
                            radio => {
                                radio.checked = radio.value.toLowerCase() === colorNormalized;
                            });
                        document.querySelectorAll('#editColorPickers .edit-color-span').forEach(
                        span => {
                            span.style.borderColor = span.dataset.color.toLowerCase() ===
                                colorNormalized ? '#212529' : 'transparent';
                        });

                        new bootstrap.Modal(document.getElementById('editStatusModal')).show();
                    });
                });

                // ─── Drag & Drop Reorder ───
                const sortableEl = document.getElementById('statusSortable');
                if (sortableEl && sortableEl.children.length > 0) {
                    new Sortable(sortableEl, {
                        handle: '.drag-handle',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        onEnd: function() {
                            const order = Array.from(sortableEl.querySelectorAll('tr[data-id]'))
                                .map(row => parseInt(row.dataset.id));

                            fetch(`${window.adminBasePath}/crm/statuses/reorder`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        order: order
                                    }),
                                })
                                .then(r => r.json())
                                .then(data => {
                                    if (!data.success) {
                                        alert(data.message || 'Failed to save order.');
                                    }
                                })
                                .catch(() => alert('Network error while saving order.'));
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
