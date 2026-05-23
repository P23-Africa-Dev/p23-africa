<div class="template-editor-pane d-none" data-format-pane="html">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label mb-0">HTML Editor</label>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-html-preview-toggle>Toggle Preview</button>
    </div>
    <textarea class="form-control font-monospace" rows="14" name="html_content" data-html-editor spellcheck="false">{{ $value }}</textarea>
    <div class="border rounded p-2 mt-2 d-none" data-html-preview-surface style="min-height: 140px;"></div>
    <div class="small mt-2 text-muted">Supports headings, lists, tables, code blocks, links, and images. Unsafe tags are
        sanitized on save.</div>
</div>
