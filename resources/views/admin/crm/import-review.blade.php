<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="font-semibold text-xl text-dark dark:text-gray-200 leading-tight dark-transition mb-1">
                    {{ __('Review Import Errors') }}
                </h2>
                <p class="text-muted dark:text-gray-400 text-sm mb-0">
                    <span id="headerRowCount">{{ count($reviewRows) }}</span> row(s) need your attention before they can be imported
                </p>
            </div>
            <a href="{{ orgRoute('admin.crm.import') }}" class="btn btn-outline-secondary">
                <i class="fa fa-upload me-2"></i>Start New Import
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Toast container for instant feedback -->
            <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index:9999;"></div>

            <!-- Progress Indicator -->
            <div class="card border-0 shadow-sm dark:bg-gray-800 mb-4">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="badge bg-success rounded-pill px-3 py-2"><i class="fa fa-check me-1"></i>Step 1: Uploaded</span>
                            <i class="fa fa-chevron-right text-muted"></i>
                            <span class="badge bg-success rounded-pill px-3 py-2"><i class="fa fa-check me-1"></i>Step 2: Mapped</span>
                            <i class="fa fa-chevron-right text-muted"></i>
                            <span class="badge bg-success rounded-pill px-3 py-2"><i class="fa fa-check me-1"></i>Step 3: Imported</span>
                            <i class="fa fa-chevron-right text-muted"></i>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fa fa-wrench me-1"></i>Step 4: Fix Errors</span>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success px-3 py-2 me-1" id="metaImported"><i class="fa fa-check me-1"></i>{{ $meta['imported'] }} imported</span>
                            @if ($meta['skipped'] > 0)
                                <span class="badge bg-secondary px-3 py-2 me-1" id="metaSkipped"><i class="fa fa-forward me-1"></i>{{ $meta['skipped'] }} skipped</span>
                            @endif
                            <span class="badge bg-danger px-3 py-2" id="metaErrors"><i class="fa fa-exclamation-triangle me-1"></i><span id="errorCountBadge">{{ $meta['error_count'] }}</span> need fixing</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <i class="fa fa-info-circle me-3 mt-1 fa-lg"></i>
                    <div>
                        <strong>What happened?</strong> The rows below could not be imported automatically.
                        Fields highlighted in <span class="text-danger fw-bold">red</span> are the ones that caused the problem.
                        <ul class="mb-0 mt-1">
                            <li><strong>Edit</strong> the highlighted field(s), then click <strong class="text-success">Fix &amp; Import</strong> on that row to import it immediately</li>
                            <li>Click <strong>Skip Row</strong> to discard a row instantly</li>
                            <li>Or use <strong>Fix &amp; Import Selected</strong> to process all remaining rows at once</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ orgRoute('admin.crm.import.retry') }}" id="reviewForm">
                @csrf

                <!-- Bulk Actions Bar -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-3" id="bulkBar">
                    <div class="card-body py-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success" id="selectAllImport">
                                <i class="fa fa-check-double me-1"></i>Select All to Import
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="selectAllSkip">
                                <i class="fa fa-ban me-1"></i>Skip All
                            </button>
                            <span class="text-muted small ms-2" id="selectionSummary">
                                <span id="remainingCount">{{ count($reviewRows) }}</span> remaining
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-sm btn-outline-secondary abandon-link">
                                <i class="fa fa-times me-1"></i>Abandon &amp; Go to Leads
                            </a>
                            <button type="submit" class="btn btn-success" id="retryBtn">
                                <i class="fa fa-redo me-2"></i>Fix &amp; Import Selected
                                <span class="badge bg-white text-success ms-1" id="retryCount">{{ count($reviewRows) }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                @php
                    $fieldValidators = [
                        'email'             => 'email',
                        'year_founded'      => 'year',
                        'currency'          => 'currency',
                        'estimated_value'   => 'numeric',
                        'employee_count'    => 'numeric',
                        'last_contacted_at' => 'date',
                        'next_follow_up'    => 'date',
                        'first_name'        => 'required',
                        'last_name'         => 'text',
                        'full_name'         => 'name-or-parts',
                        'phone'             => 'text',
                        'company'           => 'text',
                    ];
                @endphp

                <!-- Error Rows container -->
                <div id="rowsContainer">
                @foreach ($reviewRows as $idx => $errorRow)
                    @php
                        $rn            = $errorRow['row_number'];
                        $type          = $errorRow['type'];
                        $parsed        = $errorRow['parsed'] ?? [];
                        $affectedField = $errorRow['affected_field'] ?? null;
                        $borderClass   = $type === 'duplicate' ? 'border-warning' : 'border-danger';
                        $badgeClass    = $type === 'duplicate' ? 'bg-warning text-dark' : 'bg-danger';
                        $typeLabel     = match($type) {
                            'duplicate' => 'Duplicate',
                            'database'  => 'Data Error',
                            default     => 'Error',
                        };
                        $hasFirstName = !empty(trim((string)($parsed['first_name'] ?? '')));
                        $hasLastName  = !empty(trim((string)($parsed['last_name'] ?? '')));
                        $fullNameSatisfied = $hasFirstName || $hasLastName;
                    @endphp

                    <div class="card border-0 shadow-sm dark:bg-gray-800 mb-3 review-row-card border-start border-3 {{ $borderClass }}"
                         data-row="{{ $rn }}"
                         data-affected-field="{{ $affectedField ?? '' }}"
                         id="rowCard_{{ $rn }}">
                        <div class="card-header bg-transparent border-0 pt-3 pb-2">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <!-- Left: error info -->
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <span class="badge {{ $badgeClass }}">Row {{ $rn }}</span>
                                        <span class="badge bg-light text-dark border">{{ $typeLabel }}</span>
                                        <span class="row-status-badge badge bg-warning text-dark" id="rowStatus_{{ $rn }}">
                                            <i class="fa fa-exclamation-triangle me-1"></i>Needs fixing
                                        </span>
                                    </div>
                                    <p class="mb-1 small fw-semibold text-danger dark:text-red-400">
                                        <i class="fa fa-exclamation-circle me-1"></i>{{ $errorRow['message'] }}
                                    </p>
                                    <p class="mb-0 small text-muted">
                                        <i class="fa fa-lightbulb text-warning me-1"></i><strong>Fix:</strong>
                                        {{ $errorRow['suggestion'] }}
                                    </p>
                                </div>

                                <!-- Right: INSTANT action buttons -->
                                <div class="text-end text-nowrap d-flex gap-1" style="min-width: 170px;">
                                    <button type="button"
                                            class="btn btn-success btn-sm px-3 py-1 btn-instant-import"
                                            data-row="{{ $rn }}">
                                        <i class="fa fa-check me-1"></i>Fix &amp; Import
                                    </button>
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm px-3 py-1 btn-instant-skip"
                                            data-row="{{ $rn }}">
                                        <i class="fa fa-ban me-1"></i>Skip Row
                                    </button>
                                    <!-- Hidden field for bulk-form fallback -->
                                    <input type="hidden"
                                           class="row-action-hidden"
                                           name="row_action[{{ $rn }}]"
                                           id="rowAction_{{ $rn }}"
                                           value="import">
                                </div>
                            </div>
                        </div>

                        <!-- Editable fields -->
                        <div class="card-body pt-0 row-edit-area">
                            <div class="row g-2">
                                @foreach ($mappableFields as $fieldKey => $fieldLabel)
                                    @php
                                        $currentVal = $parsed[$fieldKey] ?? null;
                                        if (is_array($currentVal)) $currentVal = implode(', ', $currentVal);
                                        if (in_array($fieldKey, ['organization_id', 'drive_id', 'source_type', 'source_id'])) continue;
                                        $isCoreField = in_array($fieldKey, ['first_name', 'last_name', 'full_name', 'email', 'phone', 'company']);
                                        if (!$isCoreField && $currentVal === null) continue;

                                        $isAffected    = ($affectedField === $fieldKey);
                                        $validatorType = $fieldValidators[$fieldKey] ?? 'text';

                                        if ($fieldKey === 'full_name' && $fullNameSatisfied) {
                                            $validatorType = 'text';
                                            $isAffected = false;
                                        }

                                        $inputClasses = 'form-control form-control-sm row-field-input';
                                        if ($isAffected) $inputClasses .= ' is-invalid';
                                    @endphp
                                    <div class="col-md-4 col-lg-3">
                                        <label class="form-label small mb-1 {{ $isAffected ? 'text-danger fw-semibold' : 'text-muted' }}">
                                            @if ($isAffected)
                                                <i class="fa fa-exclamation-circle me-1"></i>
                                            @endif
                                            {{ $fieldLabel }}
                                        </label>
                                        <input
                                            type="text"
                                            name="row_data[{{ $rn }}][{{ $fieldKey }}]"
                                            class="{{ $inputClasses }}"
                                            value="{{ $currentVal ?? '' }}"
                                            placeholder="{{ $fieldLabel }}"
                                            data-field="{{ $fieldKey }}"
                                            data-row="{{ $rn }}"
                                            data-validate="{{ $validatorType }}"
                                            @if ($isAffected) autofocus @endif>
                                        @if ($isAffected)
                                            <div class="field-error-msg" style="display:block;">
                                                {{ $errorRow['message'] }} — {{ $errorRow['suggestion'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Show all fields toggle -->
                            <div class="mt-2">
                                <button type="button" class="btn btn-link btn-sm p-0 text-muted toggle-all-fields"
                                        data-row="{{ $rn }}" data-expanded="0">
                                    <i class="fa fa-plus-circle me-1"></i>Show all fields
                                </button>
                            </div>

                            <!-- Hidden extra fields -->
                            <div class="extra-fields-area mt-2 d-none" data-row="{{ $rn }}">
                                <div class="row g-2">
                                    @foreach ($mappableFields as $fieldKey => $fieldLabel)
                                        @php
                                            $currentVal = $parsed[$fieldKey] ?? null;
                                            if (is_array($currentVal)) $currentVal = implode(', ', $currentVal);
                                            if (in_array($fieldKey, ['organization_id', 'drive_id', 'source_type', 'source_id', 'first_name', 'last_name', 'full_name', 'email', 'phone', 'company'])) continue;
                                            if ($currentVal !== null) continue;
                                            $validatorType = $fieldValidators[$fieldKey] ?? 'text';
                                        @endphp
                                        <div class="col-md-4 col-lg-3">
                                            <label class="form-label small text-muted mb-1">{{ $fieldLabel }}</label>
                                            <input
                                                type="text"
                                                name="row_data[{{ $rn }}][{{ $fieldKey }}]"
                                                class="form-control form-control-sm row-field-input"
                                                value=""
                                                placeholder="{{ $fieldLabel }}"
                                                data-field="{{ $fieldKey }}"
                                                data-row="{{ $rn }}"
                                                data-validate="{{ $validatorType }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

                <!-- Empty state (hidden initially, shown when all rows removed) -->
                <div class="card border-0 shadow-sm dark:bg-gray-800 mb-3 d-none" id="emptyState">
                    <div class="card-body py-5 text-center">
                        <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="fw-semibold mb-2">All rows have been processed!</h5>
                        <p class="text-muted mb-3">Every error row has been imported or skipped.</p>
                        <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-success">
                            <i class="fa fa-arrow-right me-2"></i>Go to Leads
                        </a>
                    </div>
                </div>

                <!-- Bottom Action Bar -->
                <div class="card border-0 shadow-sm dark:bg-gray-800" id="bottomBar">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <a href="{{ orgRoute('admin.crm.index') }}" class="btn btn-outline-secondary abandon-link">
                            <i class="fa fa-times me-1"></i>Abandon &amp; Go to Leads
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" id="retryBtnBottom">
                            <i class="fa fa-redo me-2"></i>Fix &amp; Import Selected
                            <span class="badge bg-white text-success ms-1" id="retryCountBottom">{{ count($reviewRows) }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            /* ── Row card states ── */
            .review-row-card {
                transition: all 0.35s ease;
                overflow: hidden;
            }
            .review-row-card.is-skipped { opacity: 0.4; }
            .review-row-card.is-skipped .row-edit-area { pointer-events: none; }
            .border-start.border-3 { border-left-width: 4px !important; }

            /* ── Slide-out animation ── */
            .review-row-card.row-removing {
                opacity: 0;
                max-height: 0 !important;
                margin-bottom: 0 !important;
                padding: 0 !important;
                transform: translateX(60px);
                pointer-events: none;
            }

            /* ── Select-all highlight pulse ── */
            .review-row-card.highlight-selected {
                box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.45) !important;
            }

            /* ── Field validation ── */
            .row-field-input { transition: border-color 0.15s, box-shadow 0.15s; }
            .row-field-input.is-invalid {
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 0.18rem rgba(220,53,69,.18) !important;
            }
            .row-field-input.is-valid {
                border-color: #198754 !important;
                box-shadow: 0 0 0 0.18rem rgba(25,135,84,.13) !important;
            }
            .row-field-input.is-invalid:focus {
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 0.22rem rgba(220,53,69,.28) !important;
            }
            .row-field-input.is-valid:focus {
                border-color: #198754 !important;
                box-shadow: 0 0 0 0.22rem rgba(25,135,84,.22) !important;
            }

            /* ── Field error message ── */
            .field-error-msg {
                font-size: 0.78rem;
                color: #dc3545;
                margin-top: 3px;
                line-height: 1.3;
            }

            /* ── Row status badge ── */
            .row-status-badge { font-size: 0.74rem; }
            .row-status-badge.is-ready {
                background-color: #198754 !important;
                color: #fff !important;
            }
            .row-status-badge.is-skipped-badge {
                background-color: #6c757d !important;
                color: #fff !important;
            }

            /* ── Busy overlay on card while AJAX in flight ── */
            .review-row-card.row-busy {
                pointer-events: none;
                opacity: 0.65;
            }
            .review-row-card.row-busy .btn-instant-import,
            .review-row-card.row-busy .btn-instant-skip {
                pointer-events: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {

            const CSRF  = document.querySelector('meta[name="csrf-token"]')?.content
                       || document.querySelector('input[name="_token"]')?.value;
            const RETRY_SINGLE_URL = @json(orgRoute('admin.crm.import.retry-single'));
            const SKIP_ALL_URL     = @json(orgRoute('admin.crm.import.skip-all'));
            const CRM_INDEX_URL    = @json(orgRoute('admin.crm.index'));

            /* ═══════════════════════════════════════════════════════
               VALIDATORS
            ═══════════════════════════════════════════════════════ */
            const validators = {
                year:     v => v.trim() === '' || /^(19|20)\d{2}$/.test(v.trim()) ? true : 'Enter a 4-digit year (e.g. 2020)',
                email:    v => v.trim() === '' || /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()) ? true : 'Enter a valid email (e.g. name@example.com)',
                currency: v => v.trim() === '' || /^[A-Za-z]{3}$/.test(v.trim()) ? true : 'Enter a 3-letter code (e.g. USD)',
                numeric:  v => v.trim() === '' || /^[\d,.\s]+$/.test(v.trim()) ? true : 'Enter a number only (e.g. 5000)',
                date:     v => v.trim() === '' || !isNaN(Date.parse(v.trim())) ? true : 'Enter a valid date (e.g. 2024-06-15)',
                required: v => v.trim() !== '' ? true : 'This field is required',
                'name-or-parts': (v, rn) => {
                    if (v.trim() !== '') return true;
                    const card = document.getElementById('rowCard_' + rn);
                    if (card) {
                        const fn = card.querySelector('[data-field="first_name"]');
                        const ln = card.querySelector('[data-field="last_name"]');
                        if ((fn && fn.value.trim()) || (ln && ln.value.trim())) return true;
                    }
                    return 'Enter a full name, or fill in First Name';
                },
                text: () => true,
            };

            function validateField(input) {
                const type   = input.dataset.validate || 'text';
                const rule   = validators[type] || validators.text;
                const rn     = input.dataset.row;
                const result = rule(input.value, rn);
                const isOk   = result === true;
                const hasVal = input.value.trim() !== '';

                input.classList.toggle('is-invalid', !isOk);
                input.classList.toggle('is-valid',   isOk && hasVal);

                let errEl = input.nextElementSibling;
                if (!errEl || !errEl.classList.contains('field-error-msg')) errEl = null;

                if (!isOk) {
                    if (!errEl) {
                        errEl = document.createElement('div');
                        errEl.className = 'field-error-msg';
                        input.insertAdjacentElement('afterend', errEl);
                    }
                    errEl.style.display = 'block';
                    errEl.textContent   = result;
                    const lbl = input.closest('.col-md-4, .col-lg-3')?.querySelector('label');
                    if (lbl) {
                        lbl.classList.add('text-danger', 'fw-semibold');
                        if (!lbl.querySelector('.lbl-err-icon')) {
                            const ic = document.createElement('i');
                            ic.className = 'fa fa-exclamation-circle me-1 lbl-err-icon';
                            lbl.prepend(ic);
                        }
                    }
                } else {
                    if (errEl) { errEl.style.display = 'none'; errEl.textContent = ''; }
                    const lbl = input.closest('.col-md-4, .col-lg-3')?.querySelector('label');
                    if (lbl) {
                        lbl.classList.remove('text-danger', 'fw-semibold');
                        lbl.querySelector('.lbl-err-icon')?.remove();
                    }
                }
                return isOk;
            }

            function validateRow(rn) {
                const card  = document.getElementById('rowCard_' + rn);
                const badge = document.getElementById('rowStatus_' + rn);
                if (!card || !badge) return true;

                const inputs = card.querySelectorAll('.row-field-input');
                let allOk = true;
                inputs.forEach(inp => { if (!validateField(inp)) allOk = false; });

                if (allOk) {
                    badge.innerHTML = '<i class="fa fa-check-circle me-1"></i>Ready to import';
                    badge.className = 'row-status-badge badge is-ready';
                } else {
                    badge.innerHTML = '<i class="fa fa-exclamation-triangle me-1"></i>Needs fixing';
                    badge.className = 'row-status-badge badge bg-warning text-dark';
                }
                return allOk;
            }

            /* ═══════════════════════════════════════════════════════
               TOAST helper
            ═══════════════════════════════════════════════════════ */
            function showToast(msg, type) {
                const cls  = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
                const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
                const html = '<div class="toast align-items-center text-white border-0 show ' + cls + '" role="alert" style="min-width:300px;">'
                    + '<div class="d-flex"><div class="toast-body"><i class="fa ' + icon + ' me-2"></i>' + msg + '</div>'
                    + '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';
                const c = document.getElementById('toastContainer');
                c.insertAdjacentHTML('beforeend', html);
                const el = c.lastElementChild;
                setTimeout(() => { el.style.transition = 'opacity 0.4s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 3500);
            }

            /* ═══════════════════════════════════════════════════════
               Animate-out a row card then remove from DOM
            ═══════════════════════════════════════════════════════ */
            function removeCard(rn) {
                const card = document.getElementById('rowCard_' + rn);
                if (!card) return;
                card.style.maxHeight = card.scrollHeight + 'px';
                requestAnimationFrame(() => { card.classList.add('row-removing'); });
                setTimeout(() => { card.remove(); checkEmpty(); }, 400);
            }

            function getRemainingCards() {
                return document.querySelectorAll('#rowsContainer .review-row-card');
            }

            function updateCounters() {
                const remaining = getRemainingCards().length;
                document.getElementById('remainingCount').textContent    = remaining;
                document.getElementById('retryCount').textContent        = remaining;
                document.getElementById('retryCountBottom').textContent  = remaining;
                document.getElementById('headerRowCount').textContent    = remaining;
                document.getElementById('errorCountBadge').textContent   = remaining;
            }

            function checkEmpty() {
                updateCounters();
                if (getRemainingCards().length === 0) {
                    document.getElementById('emptyState').classList.remove('d-none');
                    document.getElementById('bulkBar').classList.add('d-none');
                    document.getElementById('bottomBar').classList.add('d-none');
                }
            }

            /* ═══════════════════════════════════════════════════════
               Collect all field data for a row card
            ═══════════════════════════════════════════════════════ */
            function collectRowData(rn) {
                const card = document.getElementById('rowCard_' + rn);
                if (!card) return {};
                const data = {};
                card.querySelectorAll('.row-field-input').forEach(inp => {
                    if (inp.dataset.field) data[inp.dataset.field] = inp.value;
                });
                return data;
            }

            /* ═══════════════════════════════════════════════════════
               AJAX: process a single row (import or skip)
            ═══════════════════════════════════════════════════════ */
            async function processSingleRow(rn, action) {
                const card = document.getElementById('rowCard_' + rn);
                if (!card || card.classList.contains('row-busy')) return;
                card.classList.add('row-busy');

                // Update button text
                const importBtn = card.querySelector('.btn-instant-import');
                const skipBtn   = card.querySelector('.btn-instant-skip');
                if (action === 'import' && importBtn) {
                    importBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Importing...';
                }
                if (action === 'skip' && skipBtn) {
                    skipBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Skipping...';
                }

                try {
                    const resp = await fetch(RETRY_SINGLE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            row_number: rn,
                            action:     action,
                            row_data:   action === 'import' ? collectRowData(rn) : {},
                        }),
                    });

                    const json = await resp.json();

                    if (json.ok) {
                        const label = action === 'import' ? 'Imported' : 'Skipped';
                        showToast(label + ': Row ' + rn, 'success');
                        removeCard(rn);
                    } else {
                        showToast('Row ' + rn + ': ' + (json.error || 'Unknown error'), 'error');
                        card.classList.remove('row-busy');
                        if (importBtn) importBtn.innerHTML = '<i class="fa fa-check me-1"></i>Fix &amp; Import';
                        if (skipBtn)   skipBtn.innerHTML   = '<i class="fa fa-ban me-1"></i>Skip Row';
                    }
                } catch (err) {
                    showToast('Network error — please try again.', 'error');
                    card.classList.remove('row-busy');
                    if (importBtn) importBtn.innerHTML = '<i class="fa fa-check me-1"></i>Fix &amp; Import';
                    if (skipBtn)   skipBtn.innerHTML   = '<i class="fa fa-ban me-1"></i>Skip Row';
                }
            }

            /* ═══════════════════════════════════════════════════════
               Per-row button clicks
            ═══════════════════════════════════════════════════════ */
            document.getElementById('rowsContainer').addEventListener('click', function (e) {
                const importBtn = e.target.closest('.btn-instant-import');
                const skipBtn   = e.target.closest('.btn-instant-skip');

                if (importBtn) {
                    const rn = importBtn.dataset.row;
                    // Validate first
                    if (!validateRow(rn)) {
                        showToast('Row ' + rn + ' still has invalid fields. Fix them first.', 'error');
                        const firstBad = document.querySelector('#rowCard_' + rn + ' .row-field-input.is-invalid');
                        if (firstBad) { firstBad.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstBad.focus(); }
                        return;
                    }
                    processSingleRow(rn, 'import');
                }

                if (skipBtn) {
                    processSingleRow(skipBtn.dataset.row, 'skip');
                }
            });

            /* ═══════════════════════════════════════════════════════
               Select All to Import — highlight all cards green
            ═══════════════════════════════════════════════════════ */
            document.getElementById('selectAllImport').addEventListener('click', function () {
                // Set all hidden fields to 'import' + highlight
                getRemainingCards().forEach(card => {
                    const rn  = card.dataset.row;
                    const hid = document.getElementById('rowAction_' + rn);
                    if (hid) hid.value = 'import';

                    card.classList.add('highlight-selected');
                    validateRow(rn);
                });

                showToast('All rows selected for import. Click "Fix & Import Selected" to process them all at once.', 'success');

                // Remove highlight after a moment
                setTimeout(() => {
                    getRemainingCards().forEach(card => card.classList.remove('highlight-selected'));
                }, 2000);

                updateCounters();
            });

            /* ═══════════════════════════════════════════════════════
               Skip All — AJAX skip every row immediately
            ═══════════════════════════════════════════════════════ */
            document.getElementById('selectAllSkip').addEventListener('click', async function () {
                const count = getRemainingCards().length;
                if (count === 0) return;

                if (!confirm('Skip all ' + count + ' remaining row(s)? They will NOT be imported.')) return;

                this.disabled = true;
                this.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Skipping all...';

                try {
                    const resp = await fetch(SKIP_ALL_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json',
                        },
                        body: '{}',
                    });
                    const json = await resp.json();

                    if (json.ok) {
                        showToast(json.message || 'All rows skipped.', 'success');
                        // Animate all out then redirect
                        getRemainingCards().forEach(card => card.classList.add('row-removing'));
                        setTimeout(() => { window.location.href = json.redirect || CRM_INDEX_URL; }, 600);
                    } else {
                        showToast('Error: ' + (json.error || 'Unknown'), 'error');
                        this.disabled = false;
                        this.innerHTML = '<i class="fa fa-ban me-1"></i>Skip All';
                    }
                } catch (err) {
                    showToast('Network error — please try again.', 'error');
                    this.disabled = false;
                    this.innerHTML = '<i class="fa fa-ban me-1"></i>Skip All';
                }
            });

            /* ═══════════════════════════════════════════════════════
               Live validators on field inputs
            ═══════════════════════════════════════════════════════ */
            function bindFieldListeners(input) {
                if (input.dataset.validatorBound) return;
                input.dataset.validatorBound = '1';
                const handler = () => {
                    validateField(input);
                    if (input.dataset.field === 'first_name' || input.dataset.field === 'last_name') {
                        const card = document.getElementById('rowCard_' + input.dataset.row);
                        if (card) {
                            const fn = card.querySelector('[data-field="full_name"]');
                            if (fn) validateField(fn);
                        }
                    }
                    validateRow(input.dataset.row);
                };
                input.addEventListener('input',  handler);
                input.addEventListener('change', handler);
            }

            document.querySelectorAll('.row-field-input').forEach(bindFieldListeners);

            /* ═══════════════════════════════════════════════════════
               Toggle "show all fields"
            ═══════════════════════════════════════════════════════ */
            document.querySelectorAll('.toggle-all-fields').forEach(btn => {
                btn.addEventListener('click', function () {
                    const rn   = this.dataset.row;
                    const area = document.querySelector('.extra-fields-area[data-row="' + rn + '"]');
                    const exp  = this.dataset.expanded === '1';
                    if (exp) {
                        area.classList.add('d-none');
                        this.dataset.expanded = '0';
                        this.innerHTML = '<i class="fa fa-plus-circle me-1"></i>Show all fields';
                    } else {
                        area.classList.remove('d-none');
                        this.dataset.expanded = '1';
                        this.innerHTML = '<i class="fa fa-minus-circle me-1"></i>Hide extra fields';
                        area.querySelectorAll('.row-field-input').forEach(bindFieldListeners);
                    }
                });
            });

            /* ═══════════════════════════════════════════════════════
               Confirm abandon
            ═══════════════════════════════════════════════════════ */
            document.querySelectorAll('.abandon-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    if (!confirm('Abandon the review? Unfixed rows will be lost.')) e.preventDefault();
                });
            });

            /* ═══════════════════════════════════════════════════════
               Bulk submit guard (the "Fix & Import Selected" form button)
            ═══════════════════════════════════════════════════════ */
            document.getElementById('reviewForm').addEventListener('submit', function (e) {
                const remaining = getRemainingCards().length;
                if (remaining === 0) { e.preventDefault(); return; }

                // Set all remaining to import
                getRemainingCards().forEach(card => {
                    const hid = document.getElementById('rowAction_' + card.dataset.row);
                    if (hid) hid.value = 'import';
                });

                // Warn about any invalid rows
                let hasIssues = false;
                getRemainingCards().forEach(card => {
                    const badge = document.getElementById('rowStatus_' + card.dataset.row);
                    if (badge && !badge.classList.contains('is-ready')) hasIssues = true;
                });
                if (hasIssues) {
                    if (!confirm('Some rows still have validation issues. They may fail again. Continue anyway?')) {
                        e.preventDefault();
                        return;
                    }
                }

                ['retryBtn', 'retryBtnBottom'].forEach(id => {
                    const btn = document.getElementById(id);
                    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Importing...'; }
                });
            });

            /* ═══════════════════════════════════════════════════════
               PAGE LOAD — validate + focus
            ═══════════════════════════════════════════════════════ */
            getRemainingCards().forEach(card => validateRow(card.dataset.row));
            updateCounters();

            const firstInvalid = document.querySelector('.row-field-input.is-invalid');
            if (firstInvalid) {
                setTimeout(() => {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus({ preventScroll: true });
                }, 300);
            }

        }); // end DOMContentLoaded
        </script>
    @endpush
</x-app-layout>
