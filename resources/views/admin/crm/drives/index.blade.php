<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Lead Drives') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">Organize your leads into different drives</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Leads
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDriveModal">
                    <i class="fa fa-plus me-2"></i>Create Drive
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
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

            <!-- Drives Grid -->
            <div class="row">
                @forelse ($drives as $drive)
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm dark:bg-gray-800 h-100">
                            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="d-inline-block rounded-circle me-3"
                                            style="width: 16px; height: 16px; background-color: {{ $drive->color ?? '#6c757d' }};"></span>
                                        <h5 class="mb-0 text-dark dark:text-gray-100">{{ $drive->name }}</h5>
                                    </div>
                                    @if ($drive->is_default)
                                        <span class="badge bg-primary">Default</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($drive->description)
                                    <p class="text-muted dark:text-gray-400 mb-3">{{ $drive->description }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted dark:text-gray-400">
                                        <i class="fa fa-users me-1"></i>{{ $drive->leads_count }} leads
                                    </span>
                                    <a href="{{ orgRoute('admin.crm.index', ['drive' => $drive->id]) }}"
                                        class="text-primary">
                                        View Leads <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-drive"
                                        data-id="{{ $drive->id }}" data-name="{{ $drive->name }}"
                                        data-description="{{ $drive->description }}" data-color="{{ $drive->color }}"
                                        data-is-default="{{ $drive->is_default ? '1' : '0' }}">
                                        <i class="fa fa-edit me-1"></i>Edit
                                    </button>
                                    @if (!$drive->is_default && $drive->leads_count === 0)
                                        <form action="{{ orgRoute('admin.crm.drives.destroy', $drive) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this drive?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    @elseif ($drive->leads_count > 0)
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                            title="Cannot delete drives with leads">
                                            <i class="fa fa-trash me-1"></i>Delete
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm dark:bg-gray-800">
                            <div class="card-body text-center py-5">
                                <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No drives created yet</h5>
                                <p class="text-muted">Create your first drive to organize your leads.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createDriveModal">
                                    <i class="fa fa-plus me-2"></i>Create Drive
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Create Drive Modal -->
    <div class="modal fade" id="createDriveModal" tabindex="-1" aria-labelledby="createDriveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <form action="{{ orgRoute('admin.crm.drives.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title dark:text-gray-100" id="createDriveModalLabel">
                            <i class="fa fa-folder-plus me-2 text-primary"></i>Create New Drive
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="createName" class="form-label dark:text-gray-100">Drive Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control dark:bg-gray-700 dark:text-gray-100"
                                id="createName" name="name" required placeholder="e.g., NOEL Waitlist, Hot Leads">
                        </div>
                        <div class="mb-3">
                            <label for="createDescription" class="form-label dark:text-gray-100">Description</label>
                            <textarea class="form-control dark:bg-gray-700 dark:text-gray-100" id="createDescription" name="description"
                                rows="2" placeholder="Optional description for this drive"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label dark:text-gray-100">Color</label>
                            <div class="d-flex gap-2 flex-wrap">
                                @php
                                    $colors = [
                                        '#0d6efd', // Blue
                                        '#198754', // Green
                                        '#dc3545', // Red
                                        '#ffc107', // Yellow
                                        '#0dcaf0', // Cyan
                                        '#6c757d', // Gray
                                        '#6f42c1', // Purple
                                        '#fd7e14', // Orange
                                        '#20c997', // Teal
                                        '#d63384', // Pink
                                    ];
                                @endphp
                                @foreach ($colors as $color)
                                    <label class="color-preset" style="cursor: pointer;">
                                        <input type="radio" name="color" value="{{ $color }}"
                                            class="d-none" {{ $loop->first ? 'checked' : '' }}>
                                        <span class="d-inline-block rounded"
                                            style="width: 32px; height: 32px; background-color: {{ $color }}; border: 2px solid transparent;"
                                            onclick="this.parentElement.querySelectorAll('span').forEach(s => s.style.borderColor = 'transparent'); this.style.borderColor = '#212529';"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="createIsDefault" name="is_default"
                                value="1">
                            <label class="form-check-label dark:text-gray-100" for="createIsDefault">
                                Set as default drive for new leads
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i>Create Drive
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Drive Modal -->
    <div class="modal fade" id="editDriveModal" tabindex="-1" aria-labelledby="editDriveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800">
                <form id="editDriveForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title dark:text-gray-100" id="editDriveModalLabel">
                            <i class="fa fa-edit me-2 text-primary"></i>Edit Drive
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editName" class="form-label dark:text-gray-100">Drive Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control dark:bg-gray-700 dark:text-gray-100"
                                id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label dark:text-gray-100">Description</label>
                            <textarea class="form-control dark:bg-gray-700 dark:text-gray-100" id="editDescription" name="description"
                                rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label dark:text-gray-100">Color</label>
                            <div class="d-flex gap-2 flex-wrap" id="editColorPickers">
                                @foreach ($colors as $color)
                                    <label class="color-preset" style="cursor: pointer;">
                                        <input type="radio" name="color" value="{{ $color }}"
                                            class="d-none edit-color-radio">
                                        <span class="d-inline-block rounded edit-color-span"
                                            data-color="{{ $color }}"
                                            style="width: 32px; height: 32px; background-color: {{ $color }}; border: 2px solid transparent;"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editIsDefault" name="is_default"
                                value="1">
                            <label class="form-check-label dark:text-gray-100" for="editIsDefault">
                                Set as default drive for new leads
                            </label>
                        </div>
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
                transition: border-color 0.2s;
            }

            .color-preset span:hover {
                transform: scale(1.1);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editModal = document.getElementById('editDriveModal');
                const editForm = document.getElementById('editDriveForm');

                // Handle edit button clicks
                document.querySelectorAll('.edit-drive').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const description = this.dataset.description;
                        const color = this.dataset.color;
                        const isDefault = this.dataset.isDefault === '1';

                        // Update form action
                        editForm.action = `/admin/crm/drives/${id}`;

                        // Fill form fields
                        document.getElementById('editName').value = name;
                        document.getElementById('editDescription').value = description || '';
                        document.getElementById('editIsDefault').checked = isDefault;

                        // Select the correct color
                        document.querySelectorAll('.edit-color-radio').forEach(radio => {
                            radio.checked = radio.value === color;
                        });
                        document.querySelectorAll('.edit-color-span').forEach(span => {
                            span.style.borderColor = span.dataset.color === color ? '#212529' :
                                'transparent';
                        });

                        // Show modal
                        new bootstrap.Modal(editModal).show();
                    });
                });

                // Handle color selection in edit modal
                document.querySelectorAll('.edit-color-span').forEach(span => {
                    span.addEventListener('click', function() {
                        document.querySelectorAll('.edit-color-span').forEach(s => s.style.borderColor =
                            'transparent');
                        this.style.borderColor = '#212529';
                    });
                });

                // Initialize first color selection in create modal
                const firstColorSpan = document.querySelector('#createDriveModal .color-preset span');
                if (firstColorSpan) {
                    firstColorSpan.style.borderColor = '#212529';
                }
            });
        </script>
    @endpush
</x-app-layout>
