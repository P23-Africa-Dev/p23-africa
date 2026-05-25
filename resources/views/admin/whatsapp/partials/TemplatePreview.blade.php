<div class="card h-50 position-fixed w-25">
    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span>Live Preview</span>
        <span class="badge text-bg-light" data-preview-format-label>{{ strtoupper($activeFormat ?? 'JSON') }}</span>
    </div>
    <div class="card-body" style="background: linear-gradient(180deg, #f7faf7 0%, #eef5ef 100%);">
        <div class="ms-auto rounded-4 p-3 shadow-sm" data-preview-bubble
            style="max-width: 100%; background:#dcf8c6; border:1px solid #cfeab8; min-height:90px; white-space:normal;">
            <span class="text-muted">Start writing your template to preview it here.</span>
        </div>
    </div>
</div>
