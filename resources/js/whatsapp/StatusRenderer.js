const STATUS_MAP = {
    sending: {
        icon: 'spinner-border spinner-border-sm',
        wrapper: 'text-muted',
        title: 'Sending',
    },
    queued: {
        icon: 'bi bi-clock',
        wrapper: 'text-muted',
        title: 'Queued',
    },
    sent: {
        icon: 'bi bi-check2',
        wrapper: 'text-muted',
        title: 'Sent',
    },
    delivered: {
        icon: 'bi bi-check2-all',
        wrapper: 'text-muted',
        title: 'Delivered',
    },
    read: {
        icon: 'bi bi-check2-all',
        wrapper: 'text-primary',
        title: 'Read',
    },
    failed: {
        icon: 'bi bi-exclamation-triangle-fill',
        wrapper: 'text-danger',
        title: 'Failed',
    },
    pending_retry: {
        icon: 'bi bi-arrow-clockwise',
        wrapper: 'text-warning',
        title: 'Retry pending',
    },
};

export function renderStatusIcon(status, options = {}) {
    const normalized = String(status || '').toLowerCase();
    const meta = STATUS_MAP[normalized];

    if (!meta) {
        return '';
    }

    const ariaLabel = options.ariaLabel || meta.title;
    const title = options.title || meta.title;

    if (normalized === 'sending') {
        return `<span class="wa-status ${meta.wrapper}" title="${escapeAttr(title)}" aria-label="${escapeAttr(ariaLabel)}"><span class="wa-status-spinner spinner-border spinner-border-sm" role="status"></span></span>`;
    }

    return `<span class="wa-status ${meta.wrapper}" title="${escapeAttr(title)}" aria-label="${escapeAttr(ariaLabel)}"><i class="${meta.icon}"></i></span>`;
}

function escapeAttr(value) {
    return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

export function getStatusMeta(status) {
    return STATUS_MAP[String(status || '').toLowerCase()] || null;
}