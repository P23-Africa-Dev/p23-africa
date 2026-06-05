<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Event Broadcast</h2>
                <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Send WhatsApp campaigns to
                    validated event audiences, saved contacts, or manual numbers.</p>
            </div>
            <form method="POST" action="{{ orgRoute('admin.whatsapp.templates.sync') }}" id="syncTemplatesForm">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary" id="syncTemplatesBtn"
                    title="Pull latest approved templates from Meta">
                    ↺ Sync Templates from Meta
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-3">
        @include('admin.whatsapp.partials.nav')

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ orgRoute('admin.whatsapp.events.send-broadcast') }}" id="eventBroadcastForm">
            @csrf

            {{-- Step 1 --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span>Step 1 — Source &amp; Template</span>
                    <span class="badge bg-light text-dark">Queue: whatsapp</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-4">
                            <label class="form-label">Source</label>
                            <select class="form-select" name="source_type" id="sourceType">
                                <option value="event_contacts" {{ $sourceType === 'event_contacts' ? 'selected' : '' }}>
                                    Event Contacts</option>
                                <option value="saved_contacts" {{ $sourceType === 'saved_contacts' ? 'selected' : '' }}>
                                    Saved Contacts</option>
                                <option value="manual_contacts"
                                    {{ $sourceType === 'manual_contacts' ? 'selected' : '' }}>Manual Contacts</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-8">
                            <label class="form-label">Template <span class="text-danger">*</span></label>
                            <select class="form-select" name="template_id" id="templateId" required>
                                <option value="">— Select approved template —</option>
                                @foreach ($templates as $tpl)
                                    <option value="{{ $tpl->id }}">{{ $tpl->name }} ({{ $tpl->language }})
                                    </option>
                                @endforeach
                                @if ($templates->isEmpty())
                                    @foreach ($allTemplates as $tpl)
                                        <option value="{{ $tpl->id }}">{{ $tpl->name }}
                                            ({{ $tpl->language }})
                                            — {{ ucfirst($tpl->status) }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($templates->isEmpty())
                                <small class="text-warning d-block mt-1">No approved templates — use the <strong>Sync
                                        Templates</strong> button or
                                    <a href="{{ orgRoute('admin.whatsapp.templates') }}">create one</a>.</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span>Step 2 — Audience Selection</span>
                    <span id="audienceCounter" class="badge bg-secondary">—</span>
                </div>
                <div class="card-body">

                    {{-- EVENT CONTACTS --}}
                    <div data-source-panel="event_contacts">
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label">Event Filter</label>
                                <select class="form-select form-select-sm" name="event_filter" id="eventFilter">
                                    @foreach ($eventFilterOptions as $opt)
                                        <option value="{{ $opt }}"
                                            {{ $defaultFilter === $opt ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $opt)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4" id="singleEventCol">
                                <label class="form-label">Single Event</label>
                                <select class="form-select form-select-sm" name="event_id" id="eventId">
                                    <option value="">— select —</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->title }} —
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4" id="multiEventCol">
                                <label class="form-label">Multiple Events</label>
                                <select class="form-select form-select-sm" name="event_ids[]" id="eventIds" multiple
                                    size="4">
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->title }} —
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                            </div>
                        </div>
                    </div>

                    {{-- SAVED CONTACTS --}}
                    <div data-source-panel="saved_contacts" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <label class="form-label mb-0 fw-semibold">Select Contacts</label>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <input type="text" id="savedSearch" class="form-control form-control-sm"
                                    placeholder="Search name / phone…" style="width:200px;">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    id="selectAllSaved">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    id="clearAllSaved">Clear</button>
                            </div>
                        </div>
                        <div
                            style="max-height:300px;overflow-y:auto;border:1px solid #dee2e6;border-radius:.375rem;padding:.5rem;">
                            <div class="row g-1" id="savedContactsGrid">
                                @forelse ($savedContacts as $contact)
                                    <div class="col-12 col-md-6 saved-contact-item"
                                        data-name="{{ strtolower($contact->name ?? '') }}"
                                        data-phone="{{ $contact->phone }}">
                                        <label class="form-check d-flex align-items-start gap-2 mb-0 p-2 rounded"
                                            style="cursor:pointer;">
                                            <input class="form-check-input mt-1 saved-contact-checkbox" type="checkbox"
                                                name="saved_contact_ids[]" value="{{ $contact->id }}">
                                            <span>
                                                <span
                                                    class="fw-semibold small">{{ $contact->name ?: 'Unknown' }}</span>
                                                <span class="d-block small text-muted">{{ $contact->phone }}</span>
                                                <span
                                                    class="badge {{ $contact->validation_status === 'valid' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($contact->validation_status ?: 'pending') }}
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small">No saved contacts found.</div>
                                @endforelse
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">
                            If none selected, saved contacts with usable phone numbers are used.
                            <span id="savedSelectedCount" class="text-primary fw-semibold"></span>
                        </small>
                    </div>

                    {{-- MANUAL CONTACTS --}}
                    <div data-source-panel="manual_contacts" class="d-none">
                        <label class="form-label">Enter contacts (one per line)</label>
                        <textarea class="form-control" name="manual_contacts" id="manualContacts" rows="7"
                            placeholder="+2348012345678&#10;John Doe,+2348098765432&#10;+2348111222333"></textarea>
                        <small class="text-muted">
                            Format: <code>PHONE</code> or <code>NAME,PHONE</code>.
                            <span id="manualLineCount" class="text-primary fw-semibold ms-1"></span>
                        </small>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="card mb-3">
                <div class="card-header fw-semibold">Step 3 — Preview &amp; Send</div>
                <div class="card-body d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" id="previewAudienceBtn">
                        Preview Audience &amp; Message
                    </button>
                    <button type="submit" class="btn btn-success">
                        Queue Event Broadcast
                    </button>
                    <a href="{{ orgRoute('admin.whatsapp.events.campaign-history') }}"
                        class="btn btn-outline-success">Campaign History</a>
                </div>
            </div>
        </form>

        {{-- Audience preview card --}}
        <div class="card" id="audiencePreviewCard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Audience Preview</span>
                <span class="small text-muted" id="previewMeta">No preview loaded.</span>
            </div>
            <div class="card-body">
                <div id="previewLoading" class="d-none text-muted small">Loading preview…</div>
                <div id="previewEmpty" class="text-muted small">Select source + template, then click "Preview Audience
                    &amp; Message".</div>
                <div class="alert alert-danger d-none mt-2" id="previewError"></div>
                <div class="table-responsive d-none" id="previewTableWrap">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Event</th>
                                <th>Message Preview</th>
                            </tr>
                        </thead>
                        <tbody id="previewRows"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const COUNT_URL = @json(orgRoute('admin.whatsapp.events.audience-count'));
                const PREVIEW_URL = @json(orgRoute('admin.whatsapp.events.audience-preview'));

                const sourceType = document.getElementById('sourceType');
                const eventFilter = document.getElementById('eventFilter');
                const eventId = document.getElementById('eventId');
                const eventIds = document.getElementById('eventIds');
                const manualContacts = document.getElementById('manualContacts');
                const templateId = document.getElementById('templateId');
                const audienceCounter = document.getElementById('audienceCounter');
                const savedSearch = document.getElementById('savedSearch');
                const savedGrid = document.getElementById('savedContactsGrid');
                const savedCount = document.getElementById('savedSelectedCount');
                const manualLineCount = document.getElementById('manualLineCount');
                const previewBtn = document.getElementById('previewAudienceBtn');
                const previewLoading = document.getElementById('previewLoading');
                const previewEmpty = document.getElementById('previewEmpty');
                const previewTableWrap = document.getElementById('previewTableWrap');
                const previewRows = document.getElementById('previewRows');
                const previewMeta = document.getElementById('previewMeta');
                const previewError = document.getElementById('previewError');
                const form = document.getElementById('eventBroadcastForm');

                // ── Panel visibility ─────────────────────────────────────
                function togglePanels() {
                    const val = sourceType.value;
                    document.querySelectorAll('[data-source-panel]').forEach(p => {
                        p.classList.toggle('d-none', p.dataset.sourcePanel !== val);
                    });

                    // Prevent stale event selections from affecting non-event sources.
                    if (val !== 'event_contacts') {
                        if (eventId) eventId.value = '';
                        if (eventIds) {
                            Array.from(eventIds.options).forEach(o => o.selected = false);
                        }
                    }

                    updateFilterVisibility();
                    scheduleCount();
                }

                function updateFilterVisibility() {
                    if (sourceType?.value !== 'event_contacts') {
                        document.getElementById('singleEventCol')?.classList.add('d-none');
                        document.getElementById('multiEventCol')?.classList.add('d-none');
                        return;
                    }

                    const f = eventFilter ? eventFilter.value : '';
                    document.getElementById('singleEventCol')?.classList.toggle('d-none', f !== 'single');
                    document.getElementById('multiEventCol')?.classList.toggle('d-none', f !== 'multiple');
                }

                // ── Live audience counter ────────────────────────────────
                let countTimer = null;

                function scheduleCount(delay = 450) {
                    clearTimeout(countTimer);
                    countTimer = setTimeout(fetchCount, delay);
                }

                async function fetchCount() {
                    audienceCounter.textContent = '…';
                    audienceCounter.className = 'badge bg-secondary';
                    const fd = buildFd();
                    fd.delete('template_id');
                    try {
                        const r = await post(COUNT_URL, fd);
                        const n = (r.data && r.data.total != null) ? r.data.total : 0;
                        const currentSourceType = r.data?.source_type || sourceType?.value || '';
                        const eventCount = Number(r.data?.event_count || 0);
                        const selectedCount = r.data?.selected_count;
                        let label = n + ' recipient' + (n !== 1 ? 's' : '');
                        if (currentSourceType === 'event_contacts' && eventCount > 0) {
                            label += ' • ' + eventCount + ' event' + (eventCount !== 1 ? 's' : '');
                        }
                        if (currentSourceType === 'saved_contacts' && Number(selectedCount || 0) > 0) {
                            label += ' • ' + selectedCount + ' selected';
                        }
                        audienceCounter.textContent = label;
                        audienceCounter.className = n > 0 ? 'badge bg-success' : 'badge bg-warning text-dark';
                    } catch (_) {
                        audienceCounter.textContent = 'Error';
                        audienceCounter.className = 'badge bg-danger';
                    }
                }

                // ── Saved contacts ───────────────────────────────────────
                savedSearch?.addEventListener('input', () => {
                    const q = savedSearch.value.toLowerCase().trim();
                    savedGrid?.querySelectorAll('.saved-contact-item').forEach(item => {
                        const hit = !q || item.dataset.name.includes(q) || item.dataset.phone.includes(q);
                        item.classList.toggle('d-none', !hit);
                    });
                });

                function refreshSavedCount() {
                    const n = savedGrid?.querySelectorAll('.saved-contact-checkbox:checked').length ?? 0;
                    if (savedCount) savedCount.textContent = n > 0 ? n + ' selected' : '';
                    scheduleCount(200);
                }

                document.getElementById('selectAllSaved')?.addEventListener('click', () => {
                    savedGrid?.querySelectorAll('.saved-contact-item:not(.d-none) .saved-contact-checkbox')
                        .forEach(cb => cb.checked = true);
                    refreshSavedCount();
                });
                document.getElementById('clearAllSaved')?.addEventListener('click', () => {
                    savedGrid?.querySelectorAll('.saved-contact-checkbox').forEach(cb => cb.checked = false);
                    refreshSavedCount();
                });
                savedGrid?.addEventListener('change', e => {
                    if (e.target.classList.contains('saved-contact-checkbox')) refreshSavedCount();
                });

                // ── Manual contacts line count ───────────────────────────
                function refreshManualCount() {
                    const n = (manualContacts?.value ?? '').split('\n').filter(l => l.trim()).length;
                    if (manualLineCount) manualLineCount.textContent = n > 0 ? n + ' entr' + (n !== 1 ? 'ies' : 'y') : '';
                    scheduleCount(650);
                }
                manualContacts?.addEventListener('input', refreshManualCount);

                // ── Event filter reactive updates (Issues 3 & 4) ─────────
                eventFilter?.addEventListener('change', () => {
                    updateFilterVisibility();
                    if (eventFilter.value !== 'single' && eventId) eventId.value = '';
                    if (eventFilter.value !== 'multiple' && eventIds)
                        Array.from(eventIds.options).forEach(o => o.selected = false);
                    scheduleCount();
                });
                eventId?.addEventListener('change', () => {
                    if (eventId.value) {
                        eventFilter.value = 'single';
                        updateFilterVisibility();
                    }
                    scheduleCount();
                });
                eventIds?.addEventListener('change', () => {
                    if (Array.from(eventIds.selectedOptions).length > 0) {
                        eventFilter.value = 'multiple';
                        if (eventId) eventId.value = '';
                        updateFilterVisibility();
                    }
                    scheduleCount();
                });
                templateId?.addEventListener('change', scheduleCount);

                // ── Helpers ──────────────────────────────────────────────
                function buildFd() {
                    return new FormData(form);
                }

                async function post(url, fd) {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                        },
                        body: fd
                    });
                    const json = await res.json();
                    if (!res.ok || !json.success) throw new Error(json.message || 'Request failed.');
                    return json;
                }

                function esc(s) {
                    return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                        '&quot;');
                }

                // ── Full preview ─────────────────────────────────────────
                async function loadPreview() {
                    if (!templateId?.value) {
                        alert('Please select a template before previewing.');
                        return;
                    }
                    previewLoading.classList.remove('d-none');
                    previewTableWrap.classList.add('d-none');
                    previewEmpty.classList.add('d-none');
                    previewError.classList.add('d-none');
                    previewRows.innerHTML = '';
                    try {
                        const r = await post(PREVIEW_URL, buildFd());
                        const payload = r.data ?? {};
                        const samples = Array.isArray(payload.samples) ? payload.samples : [];
                        previewMeta.textContent = 'Total valid audience: ' + (payload.total ?? 0);
                        if (!samples.length) {
                            previewEmpty.textContent = 'No valid audience found for this selection.';
                            previewEmpty.classList.remove('d-none');
                            return;
                        }
                        samples.forEach(s => {
                            const tr = document.createElement('tr');
                            tr.innerHTML =
                                `<td>${esc(s.name)}</td><td>${esc(s.phone)}</td><td>${esc(s.event_name ?? '—')}</td><td class="small" style="white-space:pre-wrap;max-width:320px;">${s.message_preview ?? ''}</td>`;
                            previewRows.appendChild(tr);
                        });
                        previewTableWrap.classList.remove('d-none');
                    } catch (err) {
                        previewError.textContent = err.message ?? 'Preview failed.';
                        previewError.classList.remove('d-none');
                    } finally {
                        previewLoading.classList.add('d-none');
                    }
                }

                // ── Sync button feedback ─────────────────────────────────
                document.getElementById('syncTemplatesForm')?.addEventListener('submit', () => {
                    const btn = document.getElementById('syncTemplatesBtn');
                    if (btn) {
                        btn.disabled = true;
                        btn.textContent = 'Syncing…';
                    }
                });

                // ── Init ─────────────────────────────────────────────────
                sourceType.addEventListener('change', togglePanels);
                previewBtn?.addEventListener('click', loadPreview);
                togglePanels();
                refreshManualCount();
                scheduleCount(150);
            })();
        </script>
    @endpush
</x-app-layout>
