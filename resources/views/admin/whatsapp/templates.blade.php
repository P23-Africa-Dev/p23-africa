<x-app-layout>
    @push('head')
        <style>
            .wa-builder-v2 {
                --wa-bg-a: #fff6ed;
                --wa-bg-b: #f6fbff;
                --wa-card: #ffffff;
                --wa-border: #d8e3eb;
                --wa-title: #153446;
                --wa-muted: #5d7080;
                --wa-accent: #0b7285;
                --wa-accent-soft: #e6f5f7;
                --wa-success: #2b8a3e;
                --wa-warning: #d9480f;
            }

            .wa-builder-v2 .wa-stage {
                background:
                    radial-gradient(circle at 12% 12%, rgba(11, 114, 133, 0.12) 0, rgba(11, 114, 133, 0) 32%),
                    radial-gradient(circle at 88% 4%, rgba(217, 72, 15, 0.1) 0, rgba(217, 72, 15, 0) 28%),
                    linear-gradient(145deg, var(--wa-bg-a), var(--wa-bg-b));
                border: 1px solid #dde8ef;
                border-radius: 16px;
                padding: 1rem;
            }

            .wa-builder-v2 .wa-card {
                background: var(--wa-card);
                border: 1px solid var(--wa-border);
                border-radius: 14px;
                box-shadow: 0 8px 20px rgba(21, 52, 70, 0.06);
            }

            .wa-builder-v2 .wa-card-title {
                font-weight: 700;
                color: var(--wa-title);
                letter-spacing: .02em;
            }

            .wa-builder-v2 .wa-muted {
                color: var(--wa-muted);
                font-size: .82rem;
            }

            .wa-builder-v2 .wa-surface {
                border-radius: 12px;
                border: 1px dashed #c6d8e3;
                background: linear-gradient(180deg, #fff, #f9fcff);
                padding: .85rem;
            }

            .wa-builder-v2 .wa-pill {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                border-radius: 999px;
                border: 1px solid #bfd4dd;
                background: #fff;
                color: #204053;
                font-size: .74rem;
                padding: .18rem .55rem;
            }

            .wa-builder-v2 .wa-mode-btn.active {
                background: var(--wa-accent);
                border-color: var(--wa-accent);
                color: #fff;
            }

            .wa-builder-v2 .wa-toolbar .btn {
                font-size: .76rem;
                min-height: 30px;
            }

            .wa-builder-v2 .wa-counter {
                font-size: .74rem;
                color: var(--wa-muted);
            }

            .wa-builder-v2 .wa-phone-preview {
                min-height: 540px;
                border-radius: 18px;
                border: 1px solid #bed4c1;
                background: linear-gradient(180deg, #d4f6cd 0%, #e9fbe5 70%);
                padding: .9rem;
                display: flex;
                flex-direction: column;
            }

            .wa-builder-v2 .wa-phone-head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: .8rem;
                font-size: .72rem;
                color: #3f5f42;
            }

            .wa-builder-v2 .wa-bubble {
                align-self: flex-start;
                background: #fff;
                border: 1px solid #cde8c6;
                border-radius: 12px;
                padding: .78rem .82rem;
                max-width: 95%;
                box-shadow: 0 4px 12px rgba(33, 70, 39, 0.08);
                animation: waFadeIn .24s ease;
            }

            .wa-builder-v2 .wa-bubble-header {
                font-size: .82rem;
                font-weight: 700;
                color: #214627;
                margin-bottom: .4rem;
                word-break: break-word;
            }

            .wa-builder-v2 .wa-bubble-body {
                white-space: pre-wrap;
                word-break: break-word;
                color: #1e3522;
                font-size: .86rem;
                line-height: 1.42;
            }

            .wa-builder-v2 .wa-bubble-footer {
                margin-top: .55rem;
                font-size: .73rem;
                color: #4f6f53;
            }

            .wa-builder-v2 .wa-bubble-buttons {
                margin-top: .65rem;
                display: flex;
                flex-direction: column;
                gap: .42rem;
            }

            .wa-builder-v2 .wa-bubble-button {
                border: 1px solid #c3debe;
                border-radius: 8px;
                background: #f5fff2;
                color: #1f5e2a;
                padding: .36rem .5rem;
                font-size: .77rem;
                text-align: center;
            }

            .wa-builder-v2 .wa-status-panel {
                border-radius: 10px;
                border: 1px solid #f0d3bf;
                background: #fff4ec;
                color: #7a4521;
                font-size: .8rem;
                padding: .55rem .65rem;
            }

            .wa-builder-v2 [data-builder-section] {
                display: none;
            }

            .wa-builder-v2 [data-builder-section].active {
                display: block;
            }

            .wa-builder-v2 .wa-row-appear {
                animation: waSlideIn .18s ease;
            }

            @keyframes waFadeIn {
                from {
                    opacity: .2;
                    transform: translateY(5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes waSlideIn {
                from {
                    opacity: .2;
                    transform: translateY(-4px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (min-width: 1200px) {
                .wa-builder-v2 .wa-preview-stick {
                    position: sticky;
                    top: 88px;
                }
            }
        </style>
    @endpush

    <x-slot name="header">
        <div>
            <h2 class="font-semibold mb-1" style="font-size:1.08rem;">WhatsApp Template Builder 2.0</h2>
            <p class="mb-0" style="font-size:.77rem;color:var(--text-muted);">Meta-compatible template authoring with
                simple visual mode, advanced JSON mode, live preview, and revision submission.</p>
        </div>
    </x-slot>

    <div class="py-2 wa-builder-v2" data-meta-template-builder>
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

        <div class="wa-stage">
            <div class="row g-3">
                <div class="col-12 col-xl-7">
                    <div class="wa-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="wa-card-title">Create or Edit Template</div>
                                <div class="wa-muted">Build in simple mode, then switch to advanced JSON if needed.
                                </div>
                            </div>
                            <span class="wa-pill" id="editingStatePill">Create Mode</span>
                        </div>

                        <form id="metaTemplateBuilderForm" method="POST"
                            action="{{ orgRoute('admin.whatsapp.templates.store') }}" enctype="multipart/form-data"
                            data-store-url="{{ orgRoute('admin.whatsapp.templates.store') }}"
                            data-update-url-template="{{ str_replace('999999', '__ID__', orgRoute('admin.whatsapp.templates.update', ['template' => 999999])) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT" data-method-override disabled>
                            <input type="hidden" name="template_format" value="json">
                            <input type="hidden" name="builder_mode" value="{{ old('builder_mode', 'simple') }}"
                                data-builder-mode-input>
                            <input type="hidden" name="buttons_config" value="{{ old('buttons_config', '[]') }}"
                                data-buttons-config-input>
                            <input type="hidden" name="editor_metadata" value="{{ old('editor_metadata', '{}') }}"
                                data-editor-metadata-input>

                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Template Name</label>
                                    <input type="text" name="name" maxlength="512" class="form-control"
                                        value="{{ old('name') }}" required data-template-name-input>
                                    <div class="wa-muted mt-1">Allowed: lowercase letters, numbers, underscores.</div>
                                    <div class="wa-counter mt-1">Normalized: <span data-normalized-name>(empty)</span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category" data-template-category>
                                        @foreach ($builderCategories as $key => $description)
                                            <option value="{{ $key }}"
                                                {{ old('category', 'utility') === $key ? 'selected' : '' }}>
                                                {{ strtoupper($key) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label class="form-label">Language</label>
                                    <input type="text" class="form-control" name="language"
                                        value="{{ old('language', 'en') }}" list="builderLanguagesList" required>
                                    <datalist id="builderLanguagesList">
                                        @foreach ($builderLanguages as $languageCode)
                                            <option value="{{ $languageCode }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <div class="wa-surface mt-3">
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <div>
                                        <label class="form-label d-block mb-1">Variable Type</label>
                                        <div class="d-flex gap-2">
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="variable_type"
                                                    id="varTypeNumber" value="number"
                                                    {{ old('variable_type', 'number') === 'number' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="varTypeNumber">Number
                                                    ({{ '{' . '{1}' . '}' }})</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input" type="radio" name="variable_type"
                                                    id="varTypeNamed" value="named"
                                                    {{ old('variable_type') === 'named' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="varTypeNamed">Named
                                                    ({{ '{' . '{name}' . '}' }})</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-label d-block mb-1">Builder Mode</label>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary wa-mode-btn"
                                                data-builder-mode="simple">Simple Builder</button>
                                            <button type="button" class="btn btn-outline-secondary wa-mode-btn"
                                                data-builder-mode="advanced">Advanced JSON</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="wa-muted mt-2" data-category-help></div>
                            </div>

                            <div class="mt-3" data-builder-section="simple">
                                <div class="wa-card p-2 mb-2">
                                    <div class="wa-card-title mb-2" style="font-size:.9rem;">Header</div>
                                    <div class="row g-2">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Header Type</label>
                                            <select class="form-select" name="simple_header_type" data-header-type>
                                                <option value="none"
                                                    {{ old('simple_header_type', 'none') === 'none' ? 'selected' : '' }}>
                                                    None</option>
                                                <option value="text"
                                                    {{ old('simple_header_type') === 'text' ? 'selected' : '' }}>Text
                                                </option>
                                                <option value="image"
                                                    {{ old('simple_header_type') === 'image' ? 'selected' : '' }}>Image
                                                </option>
                                                <option value="video"
                                                    {{ old('simple_header_type') === 'video' ? 'selected' : '' }}>Video
                                                </option>
                                                <option value="document"
                                                    {{ old('simple_header_type') === 'document' ? 'selected' : '' }}>
                                                    Document</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-8" data-header-text-wrap>
                                            <label class="form-label">Header Text (max 60)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="simple_header_text"
                                                    maxlength="60" value="{{ old('simple_header_text') }}"
                                                    data-header-text>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    data-insert-variable data-target="header">Add Variable</button>
                                            </div>
                                            <div class="wa-counter mt-1"><span data-header-count>0</span>/60</div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1" data-header-media-wrap>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">Media Format</label>
                                            <select class="form-select" name="simple_header_media_format"
                                                data-header-media-format>
                                                <option value="IMAGE"
                                                    {{ old('simple_header_media_format', 'IMAGE') === 'IMAGE' ? 'selected' : '' }}>
                                                    IMAGE</option>
                                                <option value="VIDEO"
                                                    {{ old('simple_header_media_format') === 'VIDEO' ? 'selected' : '' }}>
                                                    VIDEO</option>
                                                <option value="DOCUMENT"
                                                    {{ old('simple_header_media_format') === 'DOCUMENT' ? 'selected' : '' }}>
                                                    DOCUMENT</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <label class="form-label">Media Sample Upload</label>
                                            <input type="file" class="form-control" name="media_sample_file"
                                                accept="image/*,video/*,.pdf,.doc,.docx" data-media-file>
                                            <input type="hidden" name="media_sample_type"
                                                value="{{ old('media_sample_type', 'image') }}" data-media-type>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    id="removeMediaSample" name="remove_media_sample">
                                                <label class="form-check-label" for="removeMediaSample">Remove
                                                    existing media sample</label>
                                            </div>
                                            <div class="wa-muted mt-1" data-media-summary>No uploaded sample selected.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="wa-card p-2 mb-2">
                                    <div class="wa-card-title mb-2" style="font-size:.9rem;">Body</div>
                                    <div class="wa-toolbar d-flex flex-wrap gap-1 mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-wrap-body="*|*">Bold</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-wrap-body="_|_">Italic</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-wrap-body="~|~">Strike</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-wrap-body="```|```">Monospace</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-insert-emoji>Emoji</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-insert-variable data-target="body">Add Variable</button>
                                    </div>
                                    <textarea class="form-control" rows="6" maxlength="1024" name="simple_body_text" data-body-text>{{ old('simple_body_text') }}</textarea>
                                    <div class="wa-counter mt-1"><span data-body-count>0</span>/1024</div>
                                </div>

                                <div class="wa-card p-2 mb-2">
                                    <div class="wa-card-title mb-2" style="font-size:.9rem;">Footer</div>
                                    <input type="text" class="form-control" maxlength="60"
                                        name="simple_footer_text" value="{{ old('simple_footer_text') }}"
                                        data-footer-text>
                                    <div class="wa-counter mt-1"><span data-footer-count>0</span>/60</div>
                                </div>

                                <div class="wa-card p-2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="wa-card-title" style="font-size:.9rem;">Buttons</div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-add-button-row>Add Button</button>
                                    </div>
                                    <div class="wa-muted mb-2">Supported: Quick Reply, Visit Website, Call Phone, Copy
                                        Coupon, Flow.</div>
                                    <div class="d-flex flex-column gap-2" data-buttons-list></div>
                                </div>
                            </div>

                            <div class="mt-3" data-builder-section="advanced">
                                <div class="wa-card p-2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="wa-card-title" style="font-size:.9rem;">Advanced JSON Payload
                                        </div>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-json-pretty>Pretty</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-json-lint>Validate</button>
                                        </div>
                                    </div>
                                    <textarea class="form-control font-monospace" rows="17" name="template_json" data-advanced-json>{!! old(
                                        'template_json',
                                        "{\n  \"parameter_format\": \"POSITIONAL\",\n  \"components\": [\n    {\n      \"type\": \"BODY\",\n      \"text\": \"Hello customer, your event starts soon.\"\n    }\n  ]\n}",
                                    ) !!}</textarea>
                                    <div class="wa-muted mt-2">Meta requires at least one BODY component.</div>
                                    <div class="wa-status-panel mt-2 d-none" data-json-feedback></div>
                                </div>
                            </div>

                            <div class="wa-status-panel mt-3 d-none" data-builder-validation></div>

                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-success flex-grow-1" type="submit" data-save-button>Save
                                    Template</button>
                                <button class="btn btn-outline-secondary" type="button" data-cancel-edit
                                    hidden>Cancel Edit</button>
                            </div>
                        </form>

                        <div class="mt-3 border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold small">Submit to Meta for Approval</span>
                                <span class="badge bg-info text-dark">Create or update (revision) via Graph API</span>
                            </div>
                            <p class="small text-muted mb-2">Submit current builder payload to Meta. Updates are
                                submitted as a new revision name (for example, _v2, _v3).</p>
                            <button type="button" class="btn btn-sm btn-primary w-100" id="submitToMetaBtn">Submit
                                New Template to Meta</button>
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2"
                                id="updateTemplateToMetaBtn">Submit Template Update to Meta (New Revision)</button>
                            <div id="submitToMetaResult" class="mt-2 small d-none"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="wa-preview-stick">
                        <div class="wa-card p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="wa-card-title">Live Preview</div>
                                <span class="wa-pill"><span data-preview-mode>Simple</span> mode</span>
                            </div>

                            <div class="wa-phone-preview">
                                <div class="wa-phone-head">
                                    <span>WhatsApp Preview</span>
                                    <span data-preview-language>{{ old('language', 'en') }}</span>
                                </div>

                                <div class="wa-bubble">
                                    <div class="wa-bubble-header" data-preview-header hidden></div>
                                    <div class="wa-bubble-body" data-preview-body>Start composing your message...</div>
                                    <div class="wa-bubble-footer" data-preview-footer hidden></div>
                                    <div class="wa-bubble-buttons" data-preview-buttons></div>
                                </div>
                            </div>

                            <div class="wa-surface mt-3">
                                <div class="wa-card-title" style="font-size:.84rem;">Preview Variables</div>
                                <div class="wa-muted mt-1">Used to render placeholders in preview only.</div>
                                <textarea class="form-control font-monospace mt-2" rows="4" data-preview-variables>{"name":"Ada","company":"P23 Africa","event_name":"Nigerian Business Market","event_date":"2026-05-23","location":"Lagos","seat_code":"P23-1004","phone":"+2348012345678"}</textarea>
                            </div>
                        </div>
                    </div>
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
                                        <th>Preview</th>
                                        <th class="pe-3 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="templatesTableBody">
                                    @forelse ($templates as $template)
                                        @php
                                            $status = strtolower((string) $template->status);
                                            $statusClass = match ($status) {
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                                'pending' => 'bg-warning text-dark',
                                                'paused' => 'bg-warning text-dark',
                                                'disabled' => 'bg-dark',
                                                default => 'bg-secondary',
                                            };

                                            $templatePayload = [
                                                'id' => $template->id,
                                                'name' => $template->name,
                                                'category' => $template->category,
                                                'language' => $template->language,
                                                'status' => $template->status,
                                                'template_json' => $template->template_json,
                                                'template_format' => $template->template_format,
                                                'editor_metadata' => $template->editor_metadata ?? [],
                                            ];
                                        @endphp
                                        <tr data-template-id="{{ $template->id }}">
                                            <td class="ps-3">
                                                <div class="fw-semibold">{{ $template->name }}</div>
                                                <small class="text-muted">{{ strtoupper($template->category) }} |
                                                    {{ $template->language }}</small>
                                            </td>
                                            <td>
                                                <span data-template-status-badge
                                                    data-template-status="{{ $template->status }}"
                                                    class="badge {{ $statusClass }}">{{ ucfirst($template->status) }}</span>
                                            </td>
                                            <td style="min-width:300px; max-width:460px;">
                                                <details>
                                                    <summary class="small">Open Components</summary>
                                                    <pre class="small mb-0 mt-2 p-2 rounded-2" style="background:#f3f6f8;border:1px solid #d6e1e7;">{{ json_encode($template->template_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </details>
                                            </td>
                                            <td class="pe-3 text-end" style="min-width: 260px;">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-load-template
                                                    data-template="{{ e(json_encode($templatePayload)) }}">Load</button>
                                                <form method="POST"
                                                    action="{{ orgRoute('admin.whatsapp.templates.sync') }}"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-info"
                                                        title="Re-sync this template status from Meta">Sync</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No templates
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

    @push('scripts')
        <script>
            (function() {
                const root = document.querySelector('[data-meta-template-builder]');
                if (!root) {
                    return;
                }

                const defaultVariables = @json($defaultVariables);
                const buttonTypeLabels = @json($builderButtonTypes);
                const categoryHelp = @json($builderCategories);

                const form = document.getElementById('metaTemplateBuilderForm');
                const modeInput = form.querySelector('[data-builder-mode-input]');
                const methodOverride = form.querySelector('[data-method-override]');
                const saveButton = form.querySelector('[data-save-button]');
                const cancelEditButton = form.querySelector('[data-cancel-edit]');
                const editingStatePill = document.getElementById('editingStatePill');
                const validationPanel = form.querySelector('[data-builder-validation]');
                const editorMetadataInput = form.querySelector('[data-editor-metadata-input]');
                const buttonsConfigInput = form.querySelector('[data-buttons-config-input]');
                const previewMode = root.querySelector('[data-preview-mode]');
                const previewLanguage = root.querySelector('[data-preview-language]');
                const previewHeader = root.querySelector('[data-preview-header]');
                const previewBody = root.querySelector('[data-preview-body]');
                const previewFooter = root.querySelector('[data-preview-footer]');
                const previewButtons = root.querySelector('[data-preview-buttons]');
                const previewVariablesInput = root.querySelector('[data-preview-variables]');
                const nameInput = form.querySelector('[data-template-name-input]');
                const normalizedNameTarget = form.querySelector('[data-normalized-name]');
                const categorySelect = form.querySelector('[data-template-category]');
                const categoryHelpText = form.querySelector('[data-category-help]');
                const languageInput = form.querySelector('input[name="language"]');
                const jsonInput = form.querySelector('[data-advanced-json]');
                const jsonFeedback = form.querySelector('[data-json-feedback]');
                const bodyInput = form.querySelector('[data-body-text]');
                const footerInput = form.querySelector('[data-footer-text]');
                const headerInput = form.querySelector('[data-header-text]');
                const headerTypeInput = form.querySelector('[data-header-type]');
                const headerMediaFormat = form.querySelector('[data-header-media-format]');
                const headerTextWrap = form.querySelector('[data-header-text-wrap]');
                const headerMediaWrap = form.querySelector('[data-header-media-wrap]');
                const mediaFileInput = form.querySelector('[data-media-file]');
                const mediaTypeInput = form.querySelector('[data-media-type]');
                const mediaSummary = form.querySelector('[data-media-summary]');
                const bodyCount = form.querySelector('[data-body-count]');
                const footerCount = form.querySelector('[data-footer-count]');
                const headerCount = form.querySelector('[data-header-count]');
                const sections = Array.from(form.querySelectorAll('[data-builder-section]'));
                const modeButtons = Array.from(form.querySelectorAll('[data-builder-mode]'));
                const buttonsList = form.querySelector('[data-buttons-list]');

                let editingTemplateId = null;
                let buttonsState = [];

                function normalizeTemplateName(name) {
                    let value = String(name || '').trim().toLowerCase();
                    value = value.replace(/[^a-z0-9]+/g, '_');
                    value = value.replace(/_+/g, '_').replace(/^_+|_+$/g, '');
                    if (value && /^\d/.test(value)) {
                        value = 'template_' + value;
                    }

                    return value.slice(0, 512);
                }

                function escapeHtml(value) {
                    return String(value || '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function showValidation(messages, type = 'danger') {
                    if (!messages.length) {
                        validationPanel.classList.add('d-none');
                        validationPanel.innerHTML = '';
                        return;
                    }

                    validationPanel.className = `wa-status-panel mt-3 alert alert-${type}`;
                    validationPanel.innerHTML = messages.map((message) => `<div>${escapeHtml(message)}</div>`).join('');
                }

                function setCategoryHelp() {
                    const key = String(categorySelect.value || '').toLowerCase();
                    categoryHelpText.textContent = categoryHelp[key] ||
                        'Choose the category that best matches message intent.';
                }

                function setNamePreview() {
                    const normalized = normalizeTemplateName(nameInput.value || '');
                    normalizedNameTarget.textContent = normalized || '(empty)';
                }

                function getVariableType() {
                    return form.querySelector('input[name="variable_type"]:checked')?.value === 'named' ? 'named' :
                    'number';
                }

                function updateCounters() {
                    bodyCount.textContent = String((bodyInput.value || '').length);
                    footerCount.textContent = String((footerInput.value || '').length);
                    headerCount.textContent = String((headerInput.value || '').length);
                }

                function setMode(mode) {
                    const nextMode = mode === 'advanced' ? 'advanced' : 'simple';
                    modeInput.value = nextMode;

                    sections.forEach((section) => {
                        section.classList.toggle('active', section.getAttribute('data-builder-section') ===
                            nextMode);
                    });

                    modeButtons.forEach((button) => {
                        const active = button.getAttribute('data-builder-mode') === nextMode;
                        button.classList.toggle('active', active);
                    });

                    previewMode.textContent = nextMode === 'advanced' ? 'Advanced' : 'Simple';
                    refreshPreview();
                }

                function toggleHeaderSections() {
                    const type = String(headerTypeInput.value || 'none');
                    headerTextWrap.hidden = type !== 'text';
                    headerMediaWrap.hidden = !['image', 'video', 'document'].includes(type);

                    if (['image', 'video', 'document'].includes(type)) {
                        mediaTypeInput.value = type;
                    }
                }

                function wrapBodyText(open, close) {
                    const textarea = bodyInput;
                    const start = textarea.selectionStart || 0;
                    const end = textarea.selectionEnd || 0;
                    const value = textarea.value || '';
                    const selected = value.slice(start, end) || 'text';
                    textarea.value = value.slice(0, start) + open + selected + close + value.slice(end);
                    const cursor = start + open.length + selected.length + close.length;
                    textarea.selectionStart = cursor;
                    textarea.selectionEnd = cursor;
                    textarea.focus();
                    updateCounters();
                    refreshPreview();
                }

                function findNextNumberToken(text) {
                    const matches = Array.from(String(text || '').matchAll(/\{\{\s*(\d+)\s*\}\}/g));
                    const max = matches.reduce((carry, item) => Math.max(carry, Number(item[1] || 0)), 0);
                    return '{{ ' + Math.max(1, max + 1) + ' }}';
                }

                function askForNamedVariable() {
                    const sample = defaultVariables.join(', ');
                    const response = window.prompt(`Enter variable name. Suggested: ${sample}`);
                    if (!response) {
                        return '';
                    }

                    const normalized = String(response).trim().toLowerCase().replace(/[^a-z0-9_]/g, '_').replace(/_+/g, '_')
                        .replace(/^_+|_+$/g, '');
                    if (!normalized || !/^[a-z][a-z0-9_]{0,30}$/.test(normalized)) {
                        showValidation([
                            'Named variable must start with a letter and use lowercase letters, numbers, or underscores.'
                        ]);
                        return '';
                    }

                    return '{{ ' + normalized + ' }}';
                }

                function insertVariable(target) {
                    const variableType = getVariableType();
                    const token = variableType === 'named' ?
                        askForNamedVariable() :
                        findNextNumberToken(target.value || '');

                    if (!token) {
                        return;
                    }

                    const start = target.selectionStart || target.value.length;
                    const end = target.selectionEnd || target.value.length;
                    target.value = target.value.slice(0, start) + token + target.value.slice(end);
                    target.selectionStart = start + token.length;
                    target.selectionEnd = start + token.length;
                    target.focus();
                    updateCounters();
                    refreshPreview();
                }

                function parseButtonsFromInput() {
                    const raw = buttonsConfigInput.value || '[]';
                    try {
                        const parsed = JSON.parse(raw);
                        return Array.isArray(parsed) ? parsed : [];
                    } catch (_) {
                        return [];
                    }
                }

                function serializeButtons() {
                    buttonsConfigInput.value = JSON.stringify(buttonsState);
                }

                function getButtonValueHint(type) {
                    if (type === 'visit_website') {
                        return 'https://example.com/path/{{ 1 }}';
                    }
                    if (type === 'call_phone') {
                        return '+2348012345678';
                    }
                    if (type === 'copy_coupon') {
                        return 'PROMO2026';
                    }
                    if (type === 'flow') {
                        return 'flow_id_or_name';
                    }

                    return 'Optional value';
                }

                function renderButtons() {
                    buttonsList.innerHTML = '';

                    if (!buttonsState.length) {
                        buttonsList.innerHTML = '<div class="wa-muted">No buttons yet.</div>';
                        serializeButtons();
                        refreshPreview();
                        return;
                    }

                    buttonsState.forEach((button, index) => {
                        const row = document.createElement('div');
                        row.className = 'wa-surface wa-row-appear';
                        row.innerHTML = `
                            <div class="row g-2 align-items-end" data-button-index="${index}">
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-select form-select-sm" data-button-field="type">
                                        ${Object.entries(buttonTypeLabels).map(([value, label]) => `<option value="${value}" ${button.type === value ? 'selected' : ''}>${label}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Label</label>
                                    <input class="form-control form-control-sm" maxlength="25" data-button-field="label" value="${escapeHtml(button.label || '')}">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Value</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control" data-button-field="value" value="${escapeHtml(button.value || '')}" placeholder="${escapeHtml(getButtonValueHint(button.type || 'quick_reply'))}">
                                        <button class="btn btn-outline-secondary" type="button" data-button-variable ${button.type === 'quick_reply' ? 'disabled' : ''}>Var</button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-1 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-remove-button>&times;</button>
                                </div>
                            </div>
                        `;
                        buttonsList.appendChild(row);
                    });

                    serializeButtons();
                    refreshPreview();
                }

                function parsePreviewVariables() {
                    try {
                        const parsed = JSON.parse(previewVariablesInput.value || '{}');
                        return parsed && typeof parsed === 'object' ? parsed : {};
                    } catch (_) {
                        return {};
                    }
                }

                function applyPreviewVariables(text, variableType) {
                    const vars = parsePreviewVariables();
                    const value = String(text || '');

                    if (variableType === 'named') {
                        return value.replace(/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/g, (full, key) => {
                            if (Object.prototype.hasOwnProperty.call(vars, key)) {
                                return String(vars[key]);
                            }
                            return full;
                        });
                    }

                    const ordered = Object.values(vars);
                    return value.replace(/\{\{\s*(\d+)\s*\}\}/g, (full, index) => {
                        const i = Number(index) - 1;
                        return i >= 0 && i < ordered.length ? String(ordered[i]) : full;
                    });
                }

                function componentType(component) {
                    return String(component?.type || '').toUpperCase();
                }

                function buildSimplePayload() {
                    const errors = [];
                    const headerType = String(headerTypeInput.value || 'none');
                    const variableType = getVariableType();
                    const headerText = String(headerInput.value || '').trim();
                    const bodyText = String(bodyInput.value || '').trim();
                    const footerText = String(footerInput.value || '').trim();

                    if (!bodyText) {
                        errors.push('Body text is required.');
                    }

                    if (bodyText.length > 1024) {
                        errors.push('Body text exceeds 1024 characters.');
                    }

                    if (headerText.length > 60) {
                        errors.push('Header text exceeds 60 characters.');
                    }

                    if (footerText.length > 60) {
                        errors.push('Footer text exceeds 60 characters.');
                    }

                    const duplicateLabels = new Set();
                    buttonsState.forEach((button, index) => {
                        const labelKey = String(button.label || '').trim().toLowerCase();
                        if (!labelKey) {
                            errors.push(`Button ${index + 1} requires a label.`);
                        }
                        if (duplicateLabels.has(labelKey)) {
                            errors.push(`Duplicate button label detected: ${button.label}`);
                        }
                        if (labelKey) {
                            duplicateLabels.add(labelKey);
                        }

                        if (button.type === 'visit_website') {
                            const normalized = String(button.value || '').replace(/\{\{\s*[^\}]+\s*\}\}/g,
                            'sample');
                            if (!/^https?:\/\//i.test(normalized)) {
                                errors.push(`Button ${index + 1} website URL must start with http:// or https://`);
                            }
                        }

                        if (button.type === 'call_phone' && !/^\+[1-9]\d{6,15}$/.test(String(button.value || ''))) {
                            errors.push(
                                `Button ${index + 1} phone must be in international format (for example, +2348012345678).`
                                );
                        }
                    });

                    const components = [];

                    if (headerType === 'text' && headerText) {
                        components.push({
                            type: 'HEADER',
                            format: 'TEXT',
                            text: headerText,
                        });
                    }

                    if (['image', 'video', 'document'].includes(headerType)) {
                        const selectedFormat = String(headerMediaFormat.value || '').toUpperCase();
                        components.push({
                            type: 'HEADER',
                            format: ['IMAGE', 'VIDEO', 'DOCUMENT'].includes(selectedFormat) ?
                                selectedFormat :
                                headerType.toUpperCase(),
                        });
                    }

                    components.push({
                        type: 'BODY',
                        text: bodyText,
                    });

                    if (footerText) {
                        components.push({
                            type: 'FOOTER',
                            text: footerText,
                        });
                    }

                    if (buttonsState.length) {
                        components.push({
                            type: 'BUTTONS',
                            buttons: buttonsState
                                .filter((button) => button.type && button.label)
                                .map((button) => {
                                    if (button.type === 'quick_reply') {
                                        return {
                                            type: 'QUICK_REPLY',
                                            text: button.label,
                                        };
                                    }

                                    if (button.type === 'visit_website') {
                                        return {
                                            type: 'URL',
                                            text: button.label,
                                            url: button.value,
                                        };
                                    }

                                    if (button.type === 'call_phone') {
                                        return {
                                            type: 'PHONE_NUMBER',
                                            text: button.label,
                                            phone_number: button.value,
                                        };
                                    }

                                    if (button.type === 'copy_coupon') {
                                        return {
                                            type: 'COPY_CODE',
                                            text: button.label,
                                            coupon_code: button.value,
                                        };
                                    }

                                    return {
                                        type: 'FLOW',
                                        text: button.label,
                                        flow_id: button.value,
                                    };
                                }),
                        });
                    }

                    return {
                        errors,
                        payload: {
                            parameter_format: variableType === 'named' ? 'NAMED' : 'POSITIONAL',
                            components,
                        },
                    };
                }

                function parseAdvancedPayload() {
                    const raw = jsonInput.value || '{}';
                    const parsed = JSON.parse(raw);
                    if (!parsed || typeof parsed !== 'object') {
                        throw new Error('JSON must be an object.');
                    }
                    const components = Array.isArray(parsed.components) ? parsed.components : [];
                    if (!components.length) {
                        throw new Error('JSON must include a non-empty components array.');
                    }
                    return parsed;
                }

                function renderPreviewFromPayload(payload) {
                    const components = Array.isArray(payload?.components) ? payload.components : [];
                    const variableType = getVariableType();
                    const header = components.find((component) => componentType(component) === 'HEADER');
                    const body = components.find((component) => componentType(component) === 'BODY');
                    const footer = components.find((component) => componentType(component) === 'FOOTER');
                    const buttons = components.find((component) => componentType(component) === 'BUTTONS');

                    let headerText = '';
                    if (header && String(header.format || '').toUpperCase() === 'TEXT') {
                        headerText = applyPreviewVariables(String(header.text || ''), variableType);
                    }
                    if (header && String(header.format || '').toUpperCase() !== 'TEXT') {
                        headerText = `[${String(header.format || 'MEDIA').toUpperCase()} HEADER]`;
                    }

                    const bodyText = applyPreviewVariables(String(body?.text || ''), variableType);
                    const footerText = applyPreviewVariables(String(footer?.text || ''), variableType);

                    previewHeader.textContent = headerText;
                    previewHeader.hidden = !headerText;
                    previewBody.textContent = bodyText || 'Start composing your message...';
                    previewFooter.textContent = footerText;
                    previewFooter.hidden = !footerText;

                    previewButtons.innerHTML = '';
                    const buttonItems = Array.isArray(buttons?.buttons) ? buttons.buttons : [];
                    buttonItems.forEach((button) => {
                        const item = document.createElement('div');
                        item.className = 'wa-bubble-button';
                        item.textContent = String(button.text || button.type || 'Button');
                        previewButtons.appendChild(item);
                    });
                }

                function refreshPreview() {
                    previewLanguage.textContent = String(languageInput.value || 'en').trim() || 'en';
                    updateCounters();
                    setNamePreview();
                    setCategoryHelp();
                    toggleHeaderSections();

                    try {
                        if (modeInput.value === 'advanced') {
                            const payload = parseAdvancedPayload();
                            renderPreviewFromPayload(payload);
                        } else {
                            const built = buildSimplePayload();
                            renderPreviewFromPayload(built.payload);
                        }
                        showValidation([]);
                    } catch (error) {
                        showValidation([error instanceof Error ? error.message : 'Preview could not be rendered.']);
                    }
                }

                function collectMetadata() {
                    return {
                        builder_mode: modeInput.value,
                        variable_type: getVariableType(),
                        preview_variables: parsePreviewVariables(),
                        buttons_count: buttonsState.length,
                        updated_at: new Date().toISOString(),
                    };
                }

                function startEditing(templateData) {
                    const payload = templateData || {};
                    editingTemplateId = Number(payload.id || 0) || null;
                    if (!editingTemplateId) {
                        return;
                    }

                    nameInput.value = String(payload.name || '');
                    categorySelect.value = String(payload.category || 'utility').toLowerCase();
                    languageInput.value = String(payload.language || 'en');

                    const templateJson = payload.template_json && typeof payload.template_json === 'object' ?
                        payload.template_json :
                        {};

                    const components = Array.isArray(templateJson.components) ? templateJson.components : [];
                    const header = components.find((component) => componentType(component) === 'HEADER');
                    const body = components.find((component) => componentType(component) === 'BODY');
                    const footer = components.find((component) => componentType(component) === 'FOOTER');
                    const buttons = components.find((component) => componentType(component) === 'BUTTONS');

                    if (header) {
                        const format = String(header.format || '').toUpperCase();
                        if (format === 'TEXT') {
                            headerTypeInput.value = 'text';
                            headerInput.value = String(header.text || '');
                        } else if (['IMAGE', 'VIDEO', 'DOCUMENT'].includes(format)) {
                            headerTypeInput.value = format.toLowerCase();
                            headerMediaFormat.value = format;
                        }
                    } else {
                        headerTypeInput.value = 'none';
                        headerInput.value = '';
                    }

                    bodyInput.value = String(body?.text || '');
                    footerInput.value = String(footer?.text || '');

                    const parameterFormat = String(templateJson.parameter_format || '').toUpperCase();
                    if (parameterFormat === 'NAMED') {
                        form.querySelector('#varTypeNamed').checked = true;
                    } else {
                        form.querySelector('#varTypeNumber').checked = true;
                    }

                    buttonsState = Array.isArray(buttons?.buttons) ?
                        buttons.buttons.map((button) => {
                            const type = String(button.type || '').toUpperCase();
                            if (type === 'QUICK_REPLY') {
                                return {
                                    type: 'quick_reply',
                                    label: String(button.text || ''),
                                    value: '',
                                };
                            }
                            if (type === 'URL') {
                                return {
                                    type: 'visit_website',
                                    label: String(button.text || ''),
                                    value: String(button.url || ''),
                                };
                            }
                            if (type === 'PHONE_NUMBER') {
                                return {
                                    type: 'call_phone',
                                    label: String(button.text || ''),
                                    value: String(button.phone_number || ''),
                                };
                            }
                            if (type === 'COPY_CODE') {
                                return {
                                    type: 'copy_coupon',
                                    label: String(button.text || ''),
                                    value: String(button.coupon_code || ''),
                                };
                            }

                            return {
                                type: 'flow',
                                label: String(button.text || ''),
                                value: String(button.flow_id || ''),
                            };
                        }) :
                        [];

                    jsonInput.value = JSON.stringify(templateJson, null, 2);
                    renderButtons();

                    saveButton.textContent = 'Update Template';
                    editingStatePill.textContent = `Editing #${editingTemplateId}`;
                    cancelEditButton.hidden = false;
                    setMode('simple');
                }

                function stopEditing() {
                    editingTemplateId = null;
                    form.action = form.getAttribute('data-store-url');
                    methodOverride.disabled = true;
                    cancelEditButton.hidden = true;
                    editingStatePill.textContent = 'Create Mode';
                    saveButton.textContent = 'Save Template';
                }

                function getPayloadForMeta() {
                    const normalizedName = normalizeTemplateName(nameInput.value || '');
                    if (!normalizedName) {
                        throw new Error('Template name is required before submitting to Meta.');
                    }

                    const category = String(categorySelect.value || 'utility').toUpperCase();
                    const language = String(languageInput.value || 'en').trim().toLowerCase();
                    const variableType = getVariableType();

                    let payload;

                    if (modeInput.value === 'advanced') {
                        payload = parseAdvancedPayload();
                    } else {
                        const built = buildSimplePayload();
                        if (built.errors.length) {
                            throw new Error(built.errors[0]);
                        }
                        payload = built.payload;
                    }

                    const components = Array.isArray(payload.components) ? payload.components : [];
                    if (!components.length) {
                        throw new Error('Template must include at least one component.');
                    }

                    return {
                        name: normalizedName,
                        category,
                        language,
                        components,
                        variable_type: variableType,
                    };
                }

                function handleSubmit(event) {
                    try {
                        nameInput.value = normalizeTemplateName(nameInput.value || '');
                        if (!nameInput.value) {
                            event.preventDefault();
                            showValidation(['Template name is required after normalization.']);
                            return;
                        }

                        let payload;

                        if (modeInput.value === 'advanced') {
                            payload = parseAdvancedPayload();
                        } else {
                            const built = buildSimplePayload();
                            if (built.errors.length) {
                                event.preventDefault();
                                showValidation(built.errors);
                                return;
                            }
                            payload = built.payload;
                            jsonInput.value = JSON.stringify(payload, null, 2);
                        }

                        if (!Array.isArray(payload.components) || !payload.components.some((component) => componentType(
                                component) === 'BODY')) {
                            event.preventDefault();
                            showValidation(['Template payload must include one BODY component.']);
                            return;
                        }

                        serializeButtons();
                        editorMetadataInput.value = JSON.stringify(collectMetadata());

                        if (editingTemplateId) {
                            const updateTemplate = form.getAttribute('data-update-url-template') || '';
                            form.action = updateTemplate.replace('__ID__', String(editingTemplateId));
                            methodOverride.disabled = false;
                        } else {
                            form.action = form.getAttribute('data-store-url') || form.action;
                            methodOverride.disabled = true;
                        }

                        showValidation([]);
                    } catch (error) {
                        event.preventDefault();
                        showValidation([error instanceof Error ? error.message :
                            'Could not validate the template payload.']);
                    }
                }

                function updateMediaSummary() {
                    const file = mediaFileInput.files && mediaFileInput.files[0];
                    if (!file) {
                        mediaSummary.textContent = 'No uploaded sample selected.';
                        return;
                    }

                    mediaSummary.textContent = `Selected sample: ${file.name} (${Math.round(file.size / 1024)} KB)`;
                }

                function initListeners() {
                    modeButtons.forEach((button) => {
                        button.addEventListener('click', () => {
                            setMode(button.getAttribute('data-builder-mode') || 'simple');
                        });
                    });

                    form.addEventListener('submit', handleSubmit);
                    nameInput.addEventListener('input', refreshPreview);
                    categorySelect.addEventListener('change', refreshPreview);
                    languageInput.addEventListener('input', refreshPreview);
                    headerTypeInput.addEventListener('change', refreshPreview);
                    headerMediaFormat.addEventListener('change', refreshPreview);
                    headerInput.addEventListener('input', refreshPreview);
                    bodyInput.addEventListener('input', refreshPreview);
                    footerInput.addEventListener('input', refreshPreview);
                    previewVariablesInput.addEventListener('input', refreshPreview);
                    mediaFileInput.addEventListener('change', () => {
                        updateMediaSummary();
                        refreshPreview();
                    });

                    Array.from(form.querySelectorAll('input[name="variable_type"]')).forEach((radio) => {
                        radio.addEventListener('change', refreshPreview);
                    });

                    Array.from(form.querySelectorAll('[data-wrap-body]')).forEach((button) => {
                        button.addEventListener('click', () => {
                            const map = String(button.getAttribute('data-wrap-body') || '').split('|');
                            wrapBodyText(map[0] || '', map[1] || '');
                        });
                    });

                    form.querySelector('[data-insert-emoji]')?.addEventListener('click', () => {
                        const start = bodyInput.selectionStart || bodyInput.value.length;
                        const end = bodyInput.selectionEnd || bodyInput.value.length;
                        bodyInput.value = bodyInput.value.slice(0, start) + ':)' + bodyInput.value.slice(end);
                        bodyInput.selectionStart = start + 2;
                        bodyInput.selectionEnd = start + 2;
                        bodyInput.focus();
                        refreshPreview();
                    });

                    Array.from(form.querySelectorAll('[data-insert-variable]')).forEach((button) => {
                        button.addEventListener('click', () => {
                            const target = String(button.getAttribute('data-target') || 'body');
                            if (target === 'header') {
                                insertVariable(headerInput);
                                return;
                            }
                            insertVariable(bodyInput);
                        });
                    });

                    form.querySelector('[data-add-button-row]')?.addEventListener('click', () => {
                        buttonsState.push({
                            type: 'quick_reply',
                            label: 'Reply',
                            value: '',
                        });
                        renderButtons();
                    });

                    buttonsList.addEventListener('change', (event) => {
                        const target = event.target;
                        if (!(target instanceof HTMLElement)) {
                            return;
                        }

                        const row = target.closest('[data-button-index]');
                        if (!row) {
                            return;
                        }

                        const index = Number(row.getAttribute('data-button-index'));
                        if (!Number.isFinite(index) || !buttonsState[index]) {
                            return;
                        }

                        const field = target.getAttribute('data-button-field');
                        if (field === 'type') {
                            buttonsState[index].type = target.value;
                            renderButtons();
                            return;
                        }

                        if (field === 'label') {
                            buttonsState[index].label = target.value;
                        }

                        if (field === 'value') {
                            buttonsState[index].value = target.value;
                        }

                        serializeButtons();
                        refreshPreview();
                    });

                    buttonsList.addEventListener('input', (event) => {
                        const target = event.target;
                        if (!(target instanceof HTMLElement)) {
                            return;
                        }

                        const row = target.closest('[data-button-index]');
                        if (!row) {
                            return;
                        }

                        const index = Number(row.getAttribute('data-button-index'));
                        if (!Number.isFinite(index) || !buttonsState[index]) {
                            return;
                        }

                        const field = target.getAttribute('data-button-field');
                        if (field === 'label') {
                            buttonsState[index].label = target.value;
                        }
                        if (field === 'value') {
                            buttonsState[index].value = target.value;
                        }

                        serializeButtons();
                        refreshPreview();
                    });

                    buttonsList.addEventListener('click', (event) => {
                        const target = event.target;
                        if (!(target instanceof HTMLElement)) {
                            return;
                        }

                        const row = target.closest('[data-button-index]');
                        if (!row) {
                            return;
                        }

                        const index = Number(row.getAttribute('data-button-index'));
                        if (!Number.isFinite(index) || !buttonsState[index]) {
                            return;
                        }

                        if (target.hasAttribute('data-remove-button')) {
                            buttonsState.splice(index, 1);
                            renderButtons();
                            return;
                        }

                        if (target.hasAttribute('data-button-variable')) {
                            const type = buttonsState[index].type;
                            if (type === 'quick_reply') {
                                return;
                            }

                            const token = getVariableType() === 'named' ? askForNamedVariable() :
                                findNextNumberToken(buttonsState[index].value || '');
                            if (!token) {
                                return;
                            }

                            buttonsState[index].value = (buttonsState[index].value || '') + token;
                            renderButtons();
                        }
                    });

                    form.querySelector('[data-json-pretty]')?.addEventListener('click', () => {
                        try {
                            const parsed = parseAdvancedPayload();
                            jsonInput.value = JSON.stringify(parsed, null, 2);
                            jsonFeedback.className = 'wa-status-panel mt-2';
                            jsonFeedback.textContent = 'JSON formatted.';
                            jsonFeedback.classList.remove('d-none');
                            refreshPreview();
                        } catch (error) {
                            jsonFeedback.className = 'wa-status-panel mt-2 alert alert-danger';
                            jsonFeedback.textContent = error instanceof Error ? error.message : 'Invalid JSON.';
                            jsonFeedback.classList.remove('d-none');
                        }
                    });

                    form.querySelector('[data-json-lint]')?.addEventListener('click', () => {
                        try {
                            parseAdvancedPayload();
                            jsonFeedback.className = 'wa-status-panel mt-2 alert alert-success';
                            jsonFeedback.textContent = 'JSON is valid.';
                            jsonFeedback.classList.remove('d-none');
                        } catch (error) {
                            jsonFeedback.className = 'wa-status-panel mt-2 alert alert-danger';
                            jsonFeedback.textContent = error instanceof Error ? error.message : 'Invalid JSON.';
                            jsonFeedback.classList.remove('d-none');
                        }
                    });

                    cancelEditButton.addEventListener('click', () => {
                        stopEditing();
                        form.reset();
                        buttonsState = [];
                        buttonsConfigInput.value = '[]';
                        setMode('simple');
                        renderButtons();
                        refreshPreview();
                    });

                    Array.from(document.querySelectorAll('[data-load-template]')).forEach((button) => {
                        button.addEventListener('click', () => {
                            const payload = button.getAttribute('data-template');
                            if (!payload) {
                                return;
                            }

                            try {
                                const parsed = JSON.parse(payload);
                                startEditing(parsed);
                            } catch (_) {
                                showValidation(['Could not load template payload.']);
                            }
                        });
                    });
                }

                buttonsState = parseButtonsFromInput();
                if (!buttonsState.length) {
                    buttonsState = [];
                }
                renderButtons();
                initListeners();
                setMode(modeInput.value || 'simple');
                updateMediaSummary();
                refreshPreview();

                window.metaTemplateBuilderBridge = {
                    getPayloadForMeta,
                };
            })();
        </script>

        <script>
            (function() {
                const root = document.querySelector('[data-meta-template-builder]');
                const submitBtn = document.getElementById('submitToMetaBtn');
                const updateBtn = document.getElementById('updateTemplateToMetaBtn');
                const resultDiv = document.getElementById('submitToMetaResult');
                const tableBody = document.getElementById('templatesTableBody');

                if (!root || !submitBtn || !updateBtn || !resultDiv) {
                    return;
                }

                const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const SUBMIT_URL = @json(orgRoute('admin.whatsapp.templates.submit-to-meta'));
                const STATUS_POLL_URL = @json(orgRoute('admin.whatsapp.templates.status-poll'));

                function statusClass(status) {
                    const value = String(status || '').toLowerCase();
                    if (value === 'approved') {
                        return 'bg-success';
                    }
                    if (value === 'rejected') {
                        return 'bg-danger';
                    }
                    if (value === 'pending' || value === 'paused') {
                        return 'bg-warning text-dark';
                    }
                    if (value === 'disabled') {
                        return 'bg-dark';
                    }
                    return 'bg-secondary';
                }

                function showResult(message, ok = true) {
                    resultDiv.className = ok ? 'mt-2 small alert alert-success' : 'mt-2 small alert alert-danger';
                    resultDiv.textContent = message;
                }

                async function pollStatuses() {
                    if (!tableBody) {
                        return;
                    }

                    const rows = Array.from(tableBody.querySelectorAll('tr[data-template-id]'));
                    const ids = rows
                        .map((row) => Number(row.getAttribute('data-template-id')))
                        .filter((id) => Number.isFinite(id) && id > 0);

                    if (!ids.length) {
                        return;
                    }

                    const query = ids.map((id) => `ids[]=${encodeURIComponent(id)}`).join('&');

                    try {
                        const response = await fetch(`${STATUS_POLL_URL}?${query}`, {
                            headers: {
                                Accept: 'application/json',
                            },
                        });
                        const json = await response.json();
                        if (!response.ok || !json.success) {
                            return;
                        }

                        const statuses = Array.isArray(json.data?.templates) ? json.data.templates : [];
                        const map = new Map(statuses.map((item) => [Number(item.id), String(item.status || '')]));

                        rows.forEach((row) => {
                            const id = Number(row.getAttribute('data-template-id'));
                            const badge = row.querySelector('[data-template-status-badge]');
                            if (!badge || !map.has(id)) {
                                return;
                            }

                            const next = String(map.get(id) || '').toLowerCase();
                            badge.setAttribute('data-template-status', next);
                            badge.className = `badge ${statusClass(next)}`;
                            badge.textContent = next ? `${next.charAt(0).toUpperCase()}${next.slice(1)}` :
                                'Unknown';
                        });
                    } catch (_) {
                        // Silent polling failure to keep UI responsive.
                    }
                }

                async function submitToMeta(mode) {
                    try {
                        const bridge = window.metaTemplateBuilderBridge;
                        if (!bridge || typeof bridge.getPayloadForMeta !== 'function') {
                            throw new Error('Template builder payload bridge is not available.');
                        }

                        const payload = bridge.getPayloadForMeta();
                        const activeButton = mode === 'update' ? updateBtn : submitBtn;
                        const otherButton = mode === 'update' ? submitBtn : updateBtn;

                        activeButton.disabled = true;
                        otherButton.disabled = true;
                        activeButton.textContent = mode === 'update' ? 'Submitting update...' : 'Submitting...';

                        const formData = new FormData();
                        formData.append('name', payload.name);
                        formData.append('category', payload.category);
                        formData.append('language', payload.language);
                        formData.append('mode', mode);
                        formData.append('variable_type', payload.variable_type);
                        formData.append('components', JSON.stringify(payload.components));

                        const response = await fetch(SUBMIT_URL, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                Accept: 'application/json',
                            },
                            body: formData,
                        });

                        const json = await response.json();
                        if (!response.ok || !json.success) {
                            throw new Error(json.message || 'Submission failed.');
                        }

                        const status = String(json.data?.status || 'PENDING').toUpperCase();
                        const effectiveName = String(json.data?.effective_name || payload.name);
                        showResult(`Submitted as ${effectiveName}. Current Meta status: ${status}.`, true);
                        await pollStatuses();
                        window.setTimeout(() => window.location.reload(), 1100);
                    } catch (error) {
                        showResult(error instanceof Error ? error.message : 'Submission failed.', false);
                    } finally {
                        submitBtn.disabled = false;
                        updateBtn.disabled = false;
                        submitBtn.textContent = 'Submit New Template to Meta';
                        updateBtn.textContent = 'Submit Template Update to Meta (New Revision)';
                    }
                }

                submitBtn.addEventListener('click', () => submitToMeta('create'));
                updateBtn.addEventListener('click', () => submitToMeta('update'));

                window.setTimeout(pollStatuses, 1500);
                window.setInterval(pollStatuses, 15000);
            })();
        </script>
    @endpush
</x-app-layout>
