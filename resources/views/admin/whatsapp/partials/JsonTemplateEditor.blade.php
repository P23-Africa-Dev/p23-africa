<div class="template-editor-pane" data-format-pane="json">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label mb-0">Template JSON</label>
        <div class="d-flex gap-1">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-json-pretty>Pretty Print</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-json-lint>Lint</button>
        </div>
    </div>
    <textarea class="form-control font-monospace" rows="14" name="template_json" data-json-editor spellcheck="false">{{ $value }}</textarea>
    <div class="small mt-2 text-muted">Technical mode with JSON validation and formatting.</div>
    <div class="small mt-1 text-danger d-none" data-json-error></div>
</div>
