<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.25rem;">Event Broadcast</h2>
            <p class="mb-0" style="font-size:.875rem;color:var(--text-muted);">Send queued WhatsApp campaigns to
                validated event audiences, saved contacts, or manual numbers.</p>
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

        <form method="POST" action="{{ orgRoute('admin.whatsapp.events.send-broadcast') }}" id="eventBroadcastForm"
            class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Send Event Broadcast</span>
                <span class="badge bg-light text-dark">Queue: whatsapp</span>
            </div>
            <div class="card-body">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-lg-4">
                        <label class="form-label">Source Selector</label>
                        <select class="form-select" name="source_type" id="sourceType">
                            <option value="manual_contacts" {{ $sourceType === 'manual_contacts' ? 'selected' : '' }}>
                                Manual Contacts</option>
                            <option value="saved_contacts" {{ $sourceType === 'saved_contacts' ? 'selected' : '' }}>
                                Saved Contacts</option>
                            <option value="event_contacts" {{ $sourceType === 'event_contacts' ? 'selected' : '' }}>
                                Event Contacts</option>
                        </select>
                    </div>

                    <div class="col-12 col-lg-8">
                        <label class="form-label">Template</label>
                        <select class="form-select" name="template_id" id="templateId" required>
                            <option value="">Select approved template</option>
                            @foreach ($templates as $template)
                                <option value="{{ $template->id }}">
                                    {{ $template->name }} ({{ $template->language }})
                                </option>
                            @endforeach
                            @if ($templates->isEmpty())
                                @foreach ($allTemplates as $template)
                                    <option value="{{ $template->id }}">
                                        {{ $template->name }} ({{ $template->language }}) -
                                        {{ ucfirst($template->status) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="border rounded p-3 mt-3" data-source-panel="event_contacts">
                    <h6 class="mb-2">Event Contacts Source</h6>
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <label class="form-label">Event Filter</label>
                            <select class="form-select form-select-sm" name="event_filter" id="eventFilter">
                                @foreach ($eventFilterOptions as $option)
                                    <option value="{{ $option }}"
                                        {{ $defaultFilter === $option ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Single Event</label>
                            <select class="form-select form-select-sm" name="event_id">
                                <option value="">Select event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}">
                                        {{ $event->title }} -
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Multiple Events</label>
                            <select class="form-select form-select-sm" name="event_ids[]" multiple size="4">
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}">
                                        {{ $event->title }} -
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Used when filter is multiple.</small>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-3 mt-3 d-none" data-source-panel="saved_contacts">
                    <h6 class="mb-2">Saved Contacts Source</h6>
                    <div class="row g-2" style="max-height: 260px; overflow-y: auto;">
                        @forelse ($savedContacts as $contact)
                            <div class="col-12 col-md-6">
                                <label class="form-check d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="checkbox" name="saved_contact_ids[]"
                                        value="{{ $contact->id }}">
                                    <span>
                                        <span class="fw-semibold">{{ $contact->name ?: 'Unknown' }}</span>
                                        <span class="d-block small text-muted">{{ $contact->phone }}</span>
                                        <span class="d-block small">
                                            <span
                                                class="badge {{ $contact->validation_status === 'valid' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($contact->validation_status ?: 'pending') }}
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        @empty
                            <div class="col-12 text-muted">No saved contacts available.</div>
                        @endforelse
                    </div>
                    <small class="text-muted d-block mt-2">If none selected, all valid saved contacts are
                        considered.</small>
                </div>

                <div class="border rounded p-3 mt-3 d-none" data-source-panel="manual_contacts">
                    <h6 class="mb-2">Manual Contacts Source</h6>
                    <label class="form-label">Enter one contact per line</label>
                    <textarea class="form-control" name="manual_contacts" rows="6"
                        placeholder="+2348012345678&#10;Nurudeen,+2348098765432"></textarea>
                    <small class="text-muted">Format: PHONE or NAME,PHONE</small>
                </div>
            </div>
            <div class="card-footer d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-secondary" id="previewAudienceBtn">Preview Audience +
                    Message</button>
                <button type="submit" class="btn btn-success">Queue Event Broadcast</button>
                <a href="{{ orgRoute('admin.whatsapp.events.campaign-history') }}"
                    class="btn btn-outline-success">Campaign History</a>
            </div>
        </form>

        <div class="card" id="audiencePreviewCard">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Audience Preview</span>
                <span class="small text-muted" id="previewMeta">No preview loaded.</span>
            </div>
            <div class="card-body">
                <div id="previewLoading" class="d-none text-muted">Loading preview...</div>
                <div id="previewEmpty" class="text-muted">Select source + template, then click "Preview Audience +
                    Message".</div>
                <div class="table-responsive d-none" id="previewTableWrap">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Event</th>
                                <th>Preview</th>
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
                const sourceType = document.getElementById('sourceType');
                const form = document.getElementById('eventBroadcastForm');
                const previewBtn = document.getElementById('previewAudienceBtn');
                const previewLoading = document.getElementById('previewLoading');
                const previewEmpty = document.getElementById('previewEmpty');
                const previewTableWrap = document.getElementById('previewTableWrap');
                const previewRows = document.getElementById('previewRows');
                const previewMeta = document.getElementById('previewMeta');

                function togglePanels() {
                    const value = sourceType.value;
                    document.querySelectorAll('[data-source-panel]').forEach((panel) => {
                        panel.classList.toggle('d-none', panel.getAttribute('data-source-panel') !== value);
                    });
                }

                async function loadPreview() {
                    previewLoading.classList.remove('d-none');
                    previewTableWrap.classList.add('d-none');
                    previewEmpty.classList.add('d-none');
                    previewRows.innerHTML = '';

                    const formData = new FormData(form);
                    const token = formData.get('_token');

                    try {
                        const response = await fetch("{{ orgRoute('admin.whatsapp.events.audience-preview') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Failed to preview audience.');
                        }

                        const payload = data.data || {};
                        const samples = Array.isArray(payload.samples) ? payload.samples : [];

                        previewMeta.textContent = `Total valid audience: ${payload.total || 0}`;

                        if (samples.length === 0) {
                            previewEmpty.textContent = 'No valid audience found for this selection.';
                            previewEmpty.classList.remove('d-none');
                            return;
                        }

                        samples.forEach((sample) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${sample.name || ''}</td>
                                <td>${sample.phone || ''}</td>
                                <td>${sample.event_name || '-'}</td>
                                <td>${sample.message_preview || ''}</td>
                            `;
                            previewRows.appendChild(row);
                        });

                        previewTableWrap.classList.remove('d-none');
                    } catch (error) {
                        previewEmpty.textContent = error.message || 'Preview failed.';
                        previewEmpty.classList.remove('d-none');
                    } finally {
                        previewLoading.classList.add('d-none');
                    }
                }

                sourceType.addEventListener('change', togglePanels);
                previewBtn.addEventListener('click', loadPreview);
                togglePanels();
            })();
        </script>
    @endpush
</x-app-layout>
