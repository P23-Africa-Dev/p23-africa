<x-app-layout>
    @push('head')
        <style>
            .wa-template-compact .card-header,
            .wa-template-compact .card-body,
            .wa-template-compact .card-footer {
                padding-top: .45rem;
                padding-bottom: .45rem;
            }

            .wa-template-compact .form-label {
                margin-bottom: .2rem;
                font-size: .72rem;
            }

            .wa-template-compact .form-control,
            .wa-template-compact .form-select,
            .wa-template-compact .btn,
            .wa-template-compact textarea {
                font-size: .76rem;
            }

            .wa-template-compact .btn {
                min-height: 28px;
                padding-top: .12rem;
                padding-bottom: .12rem;
            }

            .wa-template-compact table {
                font-size: .76rem;
            }

            .wa-template-compact summary {
                font-size: .74rem;
            }

            .wa-template-compact .table td,
            .wa-template-compact .table th {
                padding-top: .38rem;
                padding-bottom: .38rem;
            }
        </style>
    @endpush

    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.05rem;">WhatsApp Templates</h2>
            <p class="mb-0" style="font-size:.74rem;color:var(--text-muted);">Build templates in JSON, rich text,
                or HTML with live WhatsApp-style preview.</p>
        </div>
    </x-slot>

    <div class="py-2 wa-template-compact" data-wa-template-builder>
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

        <div class="row g-3 wa-template-workspace-row">
            <div class="col-12 col-xl-7 wa-template-editor-column">
                <div class="card wa-template-editor-card">
                    <div class="card-header fw-semibold">Create Template</div>
                    <div class="card-body">
                        <form method="POST" action="{{ orgRoute('admin.whatsapp.templates.store') }}">
                            @csrf

                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name') }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Category</label>
                                    <input type="text" name="category" class="form-control" placeholder="utility"
                                        value="{{ old('category', 'utility') }}" required>
                                </div>
                            </div>

                            <div class="row g-2 mt-1">
                                <div class="col-12">
                                    <label class="form-label">Language</label>
                                    <input type="text" name="language" class="form-control" placeholder="en"
                                        value="{{ old('language', 'en') }}" required>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Template Format</label>
                                <input type="hidden" name="template_format"
                                    value="{{ old('template_format', 'json') }}" data-template-format-input>
                                <div class="btn-group w-100" role="group" aria-label="Template Format">
                                    <button type="button"
                                        class="btn {{ old('template_format', 'json') === 'json' ? 'btn-success' : 'btn-outline-secondary' }}"
                                        data-format-switch="json">JSON</button>
                                    <button type="button"
                                        class="btn {{ old('template_format') === 'rich_text' ? 'btn-success' : 'btn-outline-secondary' }}"
                                        data-format-switch="rich_text">Rich Text</button>
                                    <button type="button"
                                        class="btn {{ old('template_format') === 'html' ? 'btn-success' : 'btn-outline-secondary' }}"
                                        data-format-switch="html">HTML</button>
                                </div>
                            </div>

                            <div class="border rounded p-2 mt-3 bg-body-tertiary">
                                <label class="form-label">Variables</label>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach ($defaultVariables as $variable)
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-insert-variable="{{ $variable }}">{{ '{' . '{' . $variable . '}' . '}' }}</button>
                                    @endforeach
                                </div>
                                <label class="form-label">Preview Variables (JSON)</label>
                                <textarea class="form-control font-monospace" rows="3" data-template-preview-vars>{"name":"Ada","company":"P23 Africa","date":"2026-05-23","event":"Summit"}</textarea>
                            </div>

                            <input type="hidden" name="editor_metadata" data-editor-metadata value="{}">

                            <div class="mt-3">
                                @include('admin.whatsapp.partials.JsonTemplateEditor', [
                                    'value' => old(
                                        'template_json',
                                        "{\n  \"components\": [\n    {\n      \"type\": \"BODY\",\n      \"text\": \"Hello {{ 1 }}, thanks for joining our event.\"\n    }\n  ]\n}"),
                                ])

                                @include('admin.whatsapp.partials.RichTextEditor', [
                                    'value' => old(
                                        'rich_text_content',
                                        '<p>Hello @{{ name }}</p><p>Thank you for joining @{{ company }}.</p><p><strong>Offer ends tomorrow.</strong></p>'),
                                ])

                                @include('admin.whatsapp.partials.HtmlTemplateEditor', [
                                    'value' => old(
                                        'html_content',
                                        "<h2>Welcome @{{ name }}</h2>\n<p>Your event: <strong>@{{ event }}</strong></p>\n<p>Date: @{{ date }}</p>\n<img src=\"https://via.placeholder.com/320x120\" alt=\"Banner\">"),
                                ])
                            </div>

                            <button class="btn btn-success w-100" type="submit">Save Template</button>
                        </form>

                        {{-- ── Submit to Meta for approval ─────────────────── --}}
                        <div class="mt-3 border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold small">Submit to Meta for Approval</span>
                                <span class="badge bg-info text-dark">Requires JSON format with
                                    HEADER/BODY/BUTTONS</span>
                            </div>
                            <p class="small text-muted mb-2">
                                This will send the template you just built above directly to Meta.
                                New submissions keep the same name. Updates are submitted as a revision
                                (<code>_v2</code>, <code>_v3</code>, ...), because Meta reviews updated templates as
                                new versions.
                            </p>
                            <button type="button" class="btn btn-sm btn-primary w-100" id="submitToMetaBtn">
                                Submit New Template to Meta
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2"
                                id="updateTemplateToMetaBtn">
                                Submit Template Update to Meta (New Revision)
                            </button>
                            <div id="submitToMetaResult" class="mt-2 small d-none"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5 wa-template-preview-column">
                <div class="wa-template-preview-shell">
                    @include('admin.whatsapp.partials.TemplatePreview', [
                        'activeFormat' => old('template_format', 'json'),
                    ])
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-header fw-semibold">Existing Templates</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Template</th>
                                        <th>Status</th>
                                        <th>Format</th>
                                        <th>Preview</th>
                                        <th class="pe-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="templatesTableBody">
                                    @forelse ($templates as $template)
                                        <tr data-template-id="{{ $template->id }}">
                                            <td class="ps-3">
                                                <div class="fw-semibold">{{ $template->name }}</div>
                                                <small class="text-muted">{{ $template->category }} |
                                                    {{ $template->language }}</small>
                                            </td>
                                            <td>
                                                <span data-template-status-badge
                                                    data-template-status="{{ $template->status }}"
                                                    class="badge {{ $template->status === 'approved' ? 'bg-success' : ($template->status === 'rejected' ? 'bg-danger' : ($template->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')) }}">{{ ucfirst($template->status) }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge text-bg-light">{{ strtoupper($template->template_format ?? 'json') }}</span>
                                            </td>
                                            <td style="min-width:300px; max-width:420px;">
                                                <details>
                                                    <summary class="small">Open Preview</summary>
                                                    <div class="small mt-2 rounded-3 p-2"
                                                        style="background:#dcf8c6;border:1px solid #cfeab8;">
                                                        @if (($template->template_format ?? 'json') === 'json')
                                                            <pre class="small mb-0">{{ json_encode($template->template_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                        @else
                                                            {!! $template->preview_content ?:
                                                                nl2br(e(\Illuminate\Support\Str::limit((string) $template->rendered_content, 280))) !!}
                                                        @endif
                                                    </div>
                                                </details>
                                            </td>
                                            <td class="pe-3 text-end" style="min-width: 220px;">
                                                <form method="POST"
                                                    action="{{ orgRoute('admin.whatsapp.templates.update', ['template' => $template->id]) }}"
                                                    class="d-inline-flex gap-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="name"
                                                        value="{{ $template->name }}">
                                                    <input type="hidden" name="category"
                                                        value="{{ $template->category }}">
                                                    <input type="hidden" name="language"
                                                        value="{{ $template->language }}">
                                                    <input type="hidden" name="template_format"
                                                        value="{{ $template->template_format ?? 'json' }}">
                                                    <input type="hidden" name="template_json"
                                                        value='@json($template->template_json)'>
                                                    <input type="hidden" name="rich_text_content"
                                                        value="{{ $template->template_format === 'rich_text' ? $template->editor_metadata['source_content'] ?? ($template->rendered_content ?? '') : '' }}">
                                                    <input type="hidden" name="html_content"
                                                        value="{{ $template->template_format === 'html' ? $template->editor_metadata['source_content'] ?? ($template->rendered_content ?? '') : '' }}">
                                                    <input type="hidden" name="editor_metadata"
                                                        value='@json($template->editor_metadata ?? [])'>
                                                    <button class="btn btn-sm btn-outline-success"
                                                        type="submit">Update</button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ orgRoute('admin.whatsapp.templates.sync') }}"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-info"
                                                        title="Re-sync this template status from Meta">↺ Sync</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No templates
                                                created yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">{{ $templates->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @media (min-width: 1200px) {
                .wa-template-workspace-row {
                    --wa-preview-top: 96px;
                    --wa-preview-bottom-gap: 24px;
                    --wa-preview-width: clamp(320px, 28vw, 430px);
                    --wa-workspace-height: calc(100vh - 116px);
                }

                .wa-template-editor-card {
                    height: var(--wa-workspace-height);
                }

                .wa-template-editor-card .card-body {
                    overflow-y: auto;
                    overscroll-behavior: contain;
                }

                .wa-template-preview-column {
                    min-height: 1px;
                }

                .wa-template-preview-shell {
                    position: fixed;
                    top: var(--wa-preview-top);
                    right: 1.5rem;
                    z-index: 20;
                    width: min(var(--wa-preview-width), calc(100vw - 3rem));
                    height: calc(100vh - var(--wa-preview-top) - var(--wa-preview-bottom-gap));
                }

                .wa-template-preview-shell .card {
                    height: 100%;
                }

                .wa-template-preview-shell .card-body {
                    overflow-y: auto;
                    overscroll-behavior: contain;
                }

                .wa-template-preview-shell [data-preview-bubble] {
                    max-width: 100%;
                }
            }

            @media (min-width: 1200px) and (max-width: 1399.98px) {
                .wa-template-workspace-row {
                    --wa-preview-width: 360px;
                }
            }

            @media (min-width: 1400px) {
                .wa-template-preview-shell {
                    right: max(1.5rem, calc((100vw - 1320px) / 2 + 12px));
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Keep template format switching resilient to stale script bundles.
            (function() {
                const root = document.querySelector('[data-wa-template-builder]');
                if (!root) return;

                const formatInput = root.querySelector('[data-template-format-input]');
                const formatButtons = Array.from(root.querySelectorAll('[data-format-switch]'));
                const formatPanes = Array.from(root.querySelectorAll('[data-format-pane]'));
                if (!formatInput || formatButtons.length === 0 || formatPanes.length === 0) return;

                const switchFormat = (format) => {
                    formatInput.value = format;
                    formatInput.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                    root.dispatchEvent(new CustomEvent('wa-template-format-changed'));

                    formatButtons.forEach((button) => {
                        const selected = button.getAttribute('data-format-switch') === format;
                        button.classList.toggle('btn-success', selected);
                        button.classList.toggle('btn-outline-secondary', !selected);
                    });

                    formatPanes.forEach((pane) => {
                        pane.classList.toggle('d-none', pane.getAttribute('data-format-pane') !== format);
                    });
                };

                formatButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        const format = button.getAttribute('data-format-switch') || 'json';
                        switchFormat(format);
                    });
                });

                switchFormat(formatInput.value || 'json');
            })();
        </script>

        <script>
            (function() {
                const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const SUBMIT_URL = @json(orgRoute('admin.whatsapp.templates.submit-to-meta'));
                const STATUS_POLL_URL = @json(orgRoute('admin.whatsapp.templates.status-poll'));
                const root = document.querySelector('[data-wa-template-builder]');
                const submitBtn = document.getElementById('submitToMetaBtn');
                const updateBtn = document.getElementById('updateTemplateToMetaBtn');
                const resultDiv = document.getElementById('submitToMetaResult');

                if (!root) return;

                const getStatusClass = (status) => {
                    const value = String(status || '').toLowerCase();
                    if (value === 'approved') return 'bg-success';
                    if (value === 'rejected') return 'bg-danger';
                    if (value === 'pending') return 'bg-warning text-dark';
                    return 'bg-secondary';
                };

                const showToast = (message, kind = 'success') => {
                    let host = document.getElementById('templateToastHost');
                    if (!host) {
                        host = document.createElement('div');
                        host.id = 'templateToastHost';
                        host.style.position = 'fixed';
                        host.style.top = '1rem';
                        host.style.right = '1rem';
                        host.style.zIndex = '1080';
                        document.body.appendChild(host);
                    }

                    const alert = document.createElement('div');
                    alert.className =
                        `alert alert-${kind === 'success' ? 'success' : 'danger'} shadow-sm py-2 px-3 mb-2`;
                    alert.style.minWidth = '280px';
                    alert.textContent = message;
                    host.appendChild(alert);

                    window.setTimeout(() => {
                        alert.remove();
                    }, 2800);
                };

                const pollStatuses = async () => {
                    const rows = Array.from(document.querySelectorAll('tr[data-template-id]'));
                    if (!rows.length) return;

                    const ids = rows
                        .map((row) => Number(row.getAttribute('data-template-id')))
                        .filter((id) => Number.isFinite(id) && id > 0);

                    if (!ids.length) return;

                    const query = ids.map((id) => `ids[]=${encodeURIComponent(id)}`).join('&');

                    try {
                        const res = await fetch(`${STATUS_POLL_URL}?${query}`, {
                            method: 'GET',
                            headers: {
                                Accept: 'application/json',
                            },
                        });

                        const json = await res.json();
                        if (!res.ok || !json.success) return;

                        const templates = Array.isArray(json.data?.templates) ? json.data.templates : [];
                        const statusMap = new Map(templates.map((template) => [Number(template.id), String(template
                            .status || '')]));

                        rows.forEach((row) => {
                            const id = Number(row.getAttribute('data-template-id'));
                            if (!statusMap.has(id)) return;

                            const status = statusMap.get(id);
                            const badge = row.querySelector('[data-template-status-badge]');
                            if (!badge) return;

                            const oldStatus = String(badge.getAttribute('data-template-status') || '')
                                .toLowerCase();
                            const nextStatus = String(status || '').toLowerCase();

                            if (oldStatus === nextStatus) return;

                            badge.setAttribute('data-template-status', nextStatus);
                            badge.className = `badge ${getStatusClass(nextStatus)}`;
                            badge.textContent = nextStatus ?
                                `${nextStatus.charAt(0).toUpperCase()}${nextStatus.slice(1)}` : 'Unknown';
                        });
                    } catch (_) {
                        // Silent fail for polling; page remains usable.
                    }
                };

                const submitToMeta = async (mode = 'create') => {
                    const name = root.querySelector('input[name="name"]')?.value?.trim() ?? '';
                    const category = (root.querySelector('input[name="category"]')?.value ?? 'utility').trim()
                        .toUpperCase();
                    const language = (root.querySelector('input[name="language"]')?.value ?? 'en').trim();
                    const jsonEditor = root.querySelector('textarea[name="template_json"]') ?? root
                        .querySelector('[data-json-editor]');

                    if (!name) {
                        showToast('Enter a template name first.', 'danger');
                        return;
                    }

                    let components = [];
                    try {
                        const raw = (jsonEditor?.value ?? '').trim();
                        const parsed = raw ? JSON.parse(raw) : {};
                        components = Array.isArray(parsed.components) ? parsed.components : [];
                    } catch (_) {
                        showToast('Template JSON is invalid. Fix it before submitting.', 'danger');
                        return;
                    }

                    if (!components.length) {
                        showToast('Template JSON must include at least one component.', 'danger');
                        return;
                    }

                    const activeBtn = mode === 'update' ? updateBtn : submitBtn;
                    const inactiveBtn = mode === 'update' ? submitBtn : updateBtn;

                    if (activeBtn) {
                        activeBtn.disabled = true;
                        activeBtn.textContent = mode === 'update' ? 'Submitting update…' : 'Submitting…';
                    }
                    if (inactiveBtn) {
                        inactiveBtn.disabled = true;
                    }

                    if (resultDiv) {
                        resultDiv.className = 'mt-2 small d-none';
                        resultDiv.textContent = '';
                    }

                    try {
                        const fd = new FormData();
                        fd.append('name', name);
                        fd.append('category', category);
                        fd.append('language', language);
                        fd.append('mode', mode);
                        fd.append('components', JSON.stringify(components));

                        const res = await fetch(SUBMIT_URL, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                Accept: 'application/json',
                            },
                            body: fd,
                        });

                        const json = await res.json();
                        if (!res.ok || !json.success) {
                            throw new Error(json.message || 'Submission failed.');
                        }

                        const status = String(json.data?.status || 'PENDING').toUpperCase();
                        const effectiveName = String(json.data?.effective_name || name);
                        const baseName = String(json.data?.base_name || name);

                        if (mode === 'update') {
                            showToast(
                                `Update submitted to Meta as ${effectiveName} (from ${baseName}). Status: Pending.`,
                                'success'
                            );
                        } else {
                            showToast('Submitted to Meta as Pending. Template list will refresh now.', 'success');
                        }

                        if (resultDiv) {
                            resultDiv.className = 'mt-2 small alert alert-success';
                            resultDiv.textContent = mode === 'update' ?
                                `Template update submitted as ${effectiveName}. Current status: ${status}.` :
                                `Submitted to Meta. Current status: ${status}.`;
                        }

                        await pollStatuses();
                        window.setTimeout(() => window.location.reload(), 1200);
                    } catch (error) {
                        const message = error instanceof Error ? error.message : 'Submission failed.';
                        showToast(message, 'danger');
                        if (resultDiv) {
                            resultDiv.className = 'mt-2 small alert alert-danger';
                            resultDiv.textContent = `Submission failed: ${message}`;
                        }
                    } finally {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Submit New Template to Meta';
                        }
                        if (updateBtn) {
                            updateBtn.disabled = false;
                            updateBtn.textContent = 'Submit Template Update to Meta (New Revision)';
                        }
                    }
                };

                submitBtn?.addEventListener('click', () => submitToMeta('create'));
                updateBtn?.addEventListener('click', () => submitToMeta('update'));

                // Lightweight in-page status polling.
                window.setInterval(pollStatuses, 15000);
                window.setTimeout(pollStatuses, 1500);
            })();
        </script>
    @endpush
</x-app-layout>
