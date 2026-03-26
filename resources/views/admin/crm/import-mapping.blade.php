<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Map Columns') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">
                    Map your file columns to lead fields — <strong>{{ $totalRows }}</strong> data row(s) detected
                </p>
            </div>
            <a href="{{ orgRoute('admin.crm.import') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to Upload
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>
                    <strong>Import could not start.</strong>
                    <ul class="mb-0 mt-2 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="alert alert-info d-none" id="importProcessingNotice" role="status">
                <i class="fa fa-spinner fa-spin me-2"></i>Import in progress. Please keep this page open while the file is processed.
            </div>

            <form method="POST" action="{{ orgRoute('admin.crm.import.process') }}" id="mappingForm">
                @csrf
                <input type="hidden" name="temp_path" value="{{ $tempPath }}">
                <input type="hidden" name="drive_id" value="{{ $driveId ?? '' }}">
                <input type="hidden" name="new_drive_name" value="{{ $newDriveName ?? '' }}">
                <input type="hidden" name="new_drive_color" value="{{ $newDriveColor ?? '' }}">

                <!-- Mapping Progress Indicator -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="fa fa-check me-1"></i>Step 1: Uploaded
                                </span>
                                <i class="fa fa-chevron-right text-muted"></i>
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    <i class="fa fa-columns me-1"></i>Step 2: Map Columns
                                </span>
                                <i class="fa fa-chevron-right text-muted"></i>
                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                    Step 3: Import
                                </span>
                            </div>
                            <div>
                                <span class="text-muted small" id="mappedCount">
                                    <i class="fa fa-link me-1"></i><strong id="mappedNum">0</strong> of
                                    {{ count($headers) }} columns mapped
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column Mapping Card -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark dark:text-gray-100">
                                <i class="fa fa-columns me-2 text-primary"></i>Column Mapping
                            </h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllBtn">
                                    <i class="fa fa-eraser me-1"></i>Clear All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-1" id="autoMapBtn">
                                    <i class="fa fa-magic me-1"></i>Auto-Map
                                </button>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">
                            Match each column from your file to the corresponding lead field. Columns set to "Skip" will
                            be ignored.
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th style="width: 50px;" class="text-center">#</th>
                                        <th style="width: 30%;">Your Column Header</th>
                                        <th style="width: 50px;" class="text-center"><i
                                                class="fa fa-arrow-right text-muted"></i></th>
                                        <th style="width: 35%;">Maps To Lead Field</th>
                                        <th>Sample Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($headers as $index => $header)
                                        <tr class="mapping-row" data-col="{{ $index }}">
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-light text-dark border">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fa fa-file-alt text-muted me-2"></i>
                                                    <strong
                                                        class="text-dark dark:text-gray-100">{{ $header }}</strong>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <i class="fa fa-long-arrow-alt-right text-primary mapping-arrow"></i>
                                            </td>
                                            <td>
                                                <select name="column_map[{{ $index }}]"
                                                    class="form-select form-select-sm column-mapping-select dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                                    data-col="{{ $index }}">
                                                    <option value="skip">— Skip this column —</option>
                                                    @foreach ($mappableFields as $fieldKey => $fieldLabel)
                                                        <option value="{{ $fieldKey }}"
                                                            @if (($suggestedMapping[$index] ?? '') === $fieldKey) selected @endif>
                                                            {{ $fieldLabel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="text-muted small sample-data" style="max-width: 200px;">
                                                    @php
                                                        $samples = [];
                                                        foreach ($previewRows as $pRow) {
                                                            $val = isset($pRow[$index])
                                                                ? trim((string) $pRow[$index])
                                                                : '';
                                                            if ($val !== '') {
                                                                $samples[] = $val;
                                                            }
                                                        }
                                                    @endphp
                                                    @if (!empty($samples))
                                                        @foreach (array_slice($samples, 0, 3) as $s)
                                                            <span class="d-block text-truncate"
                                                                style="max-width: 200px;"
                                                                title="{{ $s }}">{{ $s }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted fst-italic">No data</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Data Preview Card -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0 text-dark dark:text-gray-100">
                            <i class="fa fa-eye me-2 text-primary"></i>Data Preview
                            <small class="text-muted fw-normal ms-2">(first {{ count($previewRows) }} of
                                {{ $totalRows }} rows)</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm mb-0" style="font-size: 0.8rem;">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-nowrap">#</th>
                                        @foreach ($headers as $header)
                                            <th class="text-nowrap">{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($previewRows as $rIdx => $pRow)
                                        <tr>
                                            <td class="text-muted">{{ $rIdx + 1 }}</td>
                                            @foreach ($headers as $cIdx => $h)
                                                <td class="text-truncate" style="max-width: 180px;"
                                                    title="{{ $pRow[$cIdx] ?? '' }}">
                                                    {{ $pRow[$cIdx] ?? '' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <!-- Validation Alerts Area -->
                <div id="validationAlerts" class="mb-3"></div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ orgRoute('admin.crm.import') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Re-upload File
                    </a>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="text-muted small me-2">
                            <i class="fa fa-database me-1"></i>{{ $totalRows }} row(s) will be processed
                        </span>
                        <button type="submit" class="btn btn-success btn-lg" id="importBtn">
                            <i class="fa fa-check-double me-2"></i>Import {{ $totalRows }} Lead(s)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .mapping-row:hover {
                background-color: rgba(13, 110, 253, 0.03);
            }

            .mapping-row .mapping-arrow {
                transition: color 0.2s;
            }

            .mapping-row.mapped .mapping-arrow {
                color: #198754 !important;
            }

            .mapping-row.mapped td:first-child .badge {
                background-color: #198754 !important;
                color: #fff !important;
            }

            .mapping-row.has-duplicate td:first-child .badge {
                background-color: #dc3545 !important;
                color: #fff !important;
            }

            .mapping-row.has-duplicate .mapping-arrow {
                color: #dc3545 !important;
            }

            .column-mapping-select {
                min-width: 220px;
            }

            .column-mapping-select option[value="skip"] {
                color: #6c757d;
                font-style: italic;
            }

            .column-mapping-select option:disabled {
                color: #adb5bd;
                font-style: italic;
            }

            .validation-alert {
                animation: slideIn 0.2s ease-out;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-8px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            #importBtn:disabled {
                cursor: not-allowed;
                opacity: 0.65;
            }

            #importBtn.btn-blocked {
                background-color: #6c757d !important;
                border-color: #6c757d !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selects = document.querySelectorAll('.column-mapping-select');
                const mappedNum = document.getElementById('mappedNum');
                const importBtn = document.getElementById('importBtn');
                const clearAllBtn = document.getElementById('clearAllBtn');
                const autoMapBtn = document.getElementById('autoMapBtn');
                const alertsContainer = document.getElementById('validationAlerts');
                const mappingForm = document.getElementById('mappingForm');

                // Original auto-mappings from server
                const originalMappings = @json($suggestedMapping);

                // Field labels for user-friendly messages
                const fieldLabels = @json($mappableFields);

                /**
                 * Run all validations and update the UI.
                 */
                function validate() {
                    let issues = [];
                    let warnings = [];
                    let mapped = 0;

                    // Reset all select states
                    selects.forEach(sel => {
                        sel.classList.remove('is-invalid', 'border-warning');
                        sel.closest('.mapping-row').classList.remove('has-duplicate');
                    });

                    // 1. Count mapped columns
                    selects.forEach(sel => {
                        const row = sel.closest('.mapping-row');
                        if (sel.value && sel.value !== 'skip') {
                            mapped++;
                            row.classList.add('mapped');
                        } else {
                            row.classList.remove('mapped');
                        }
                    });
                    mappedNum.textContent = mapped;

                    // 2. Check: at least one column mapped
                    if (mapped === 0) {
                        issues.push({
                            type: 'danger',
                            icon: 'fa-exclamation-circle',
                            message: '<strong>No columns mapped.</strong> Please map at least one column to a lead field before importing.'
                        });
                    }

                    // 3. Check duplicate mappings
                    const fieldUsage = {}; // field => [select elements]
                    selects.forEach(sel => {
                        if (sel.value && sel.value !== 'skip') {
                            if (!fieldUsage[sel.value]) fieldUsage[sel.value] = [];
                            fieldUsage[sel.value].push(sel);
                        }
                    });

                    let hasDuplicates = false;
                    for (const [field, usedSelects] of Object.entries(fieldUsage)) {
                        if (usedSelects.length > 1) {
                            hasDuplicates = true;
                            const label = fieldLabels[field] || field;
                            const colNums = usedSelects.map(s => parseInt(s.dataset.col) + 1).join(', ');
                            issues.push({
                                type: 'danger',
                                icon: 'fa-clone',
                                message: `<strong>"${label}"</strong> is mapped to multiple columns (#${colNums}). Each field can only be mapped once.`
                            });
                            usedSelects.forEach(s => {
                                s.classList.add('is-invalid');
                                s.closest('.mapping-row').classList.add('has-duplicate');
                            });
                        }
                    }

                    // 4. Check: recommend mapping a name or email field
                    const hasFn = fieldUsage['first_name'] || fieldUsage['full_name'];
                    const hasEmail = fieldUsage['email'];
                    if (mapped > 0 && !hasFn && !hasEmail) {
                        warnings.push({
                            type: 'warning',
                            icon: 'fa-user-slash',
                            message: '<strong>Tip:</strong> No name or email column is mapped. Leads may be hard to identify. Consider mapping at least a name or email.'
                        });
                    }

                    // 5. Render alerts
                    renderAlerts(issues, warnings);

                    // 6. Enable / disable import button
                    const hasBlockingIssues = issues.length > 0;
                    importBtn.disabled = false;
                    if (hasBlockingIssues) {
                        importBtn.classList.add('btn-blocked');
                        importBtn.classList.remove('btn-success');
                        importBtn.title = 'Resolve validation issues before importing.';
                    } else {
                        importBtn.classList.remove('btn-blocked');
                        importBtn.classList.add('btn-success');
                        importBtn.title = '';
                    }

                    importBtn.dataset.blockingIssues = String(issues.length);
                }

                /**
                 * Render validation alerts in the container.
                 */
                function renderAlerts(issues, warnings) {
                    let html = '';

                    issues.forEach(issue => {
                        html += `<div class="alert alert-${issue.type} validation-alert d-flex align-items-center py-2 mb-2" role="alert">
                            <i class="fa ${issue.icon} me-2"></i>
                            <div class="small">${issue.message}</div>
                        </div>`;
                    });

                    warnings.forEach(warning => {
                        html += `<div class="alert alert-${warning.type} validation-alert d-flex align-items-center py-2 mb-2" role="alert">
                            <i class="fa ${warning.icon} me-2"></i>
                            <div class="small">${warning.message}</div>
                        </div>`;
                    });

                    alertsContainer.innerHTML = html;
                }

                // Listen for changes
                selects.forEach(sel => {
                    sel.addEventListener('change', function() {
                        syncDisabledOptions();
                        validate();
                    });
                });

                /**
                 * After any dropdown changes, disable options that are already
                 * claimed by another dropdown so users cannot pick the same
                 * field twice. The currently-selected value of each dropdown is
                 * always kept enabled in its own select so it can still be seen
                 * and changed.
                 */
                function syncDisabledOptions() {
                    // Build a set of all currently-chosen field keys
                    const taken = new Set();
                    selects.forEach(sel => {
                        if (sel.value && sel.value !== 'skip') {
                            taken.add(sel.value);
                        }
                    });

                    // For every dropdown, enable/disable options accordingly
                    selects.forEach(sel => {
                        const ownValue = sel.value; // this select's current pick
                        Array.from(sel.options).forEach(opt => {
                            if (opt.value === 'skip' || opt.value === ownValue) {
                                // Always keep "skip" and the own current value available
                                opt.disabled = false;
                                opt.title = '';
                            } else if (taken.has(opt.value)) {
                                opt.disabled = true;
                                opt.title = 'Already mapped to another column';
                            } else {
                                opt.disabled = false;
                                opt.title = '';
                            }
                        });
                    });
                }

                // Clear all
                clearAllBtn.addEventListener('click', function() {
                    selects.forEach(sel => {
                        sel.value = 'skip';
                    });
                    syncDisabledOptions();
                    validate();
                });

                // Auto-map
                autoMapBtn.addEventListener('click', function() {
                    selects.forEach((sel, i) => {
                        const suggested = originalMappings[i] || '';
                        sel.value = suggested || 'skip';
                    });
                    syncDisabledOptions();
                    validate();
                });

                // Initial validation
                syncDisabledOptions();
                validate();

                // Form submission
                mappingForm.addEventListener('submit', function(e) {
                    validate();

                    const blockingIssues = parseInt(importBtn.dataset.blockingIssues || '0', 10);
                    if (blockingIssues > 0) {
                        e.preventDefault();

                        const currentHtml = (alertsContainer.innerHTML || '').trim();
                        if (!currentHtml) {
                            alertsContainer.innerHTML = `<div class="alert alert-danger validation-alert d-flex align-items-center py-2 mb-2" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>
                                <div class="small"><strong>Import blocked.</strong> Please resolve the highlighted mapping issues and try again.</div>
                            </div>`;
                        }

                        alertsContainer.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        const firstInvalid = document.querySelector('.column-mapping-select.is-invalid');
                        if (firstInvalid) {
                            firstInvalid.focus();
                        }

                        return;
                    }

                    document.getElementById('importProcessingNotice').classList.remove('d-none');
                    clearAllBtn.disabled = true;
                    autoMapBtn.disabled = true;
                    importBtn.disabled = true;
                    importBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Importing leads...';
                });
            });
        </script>
    @endpush
</x-app-layout>
