import { renderStatusIcon } from './StatusRenderer.js';

export default class MessageRenderer {
    constructor(options = {}) {
        this.currentUserId = Number(options.currentUserId || 0);
    }

    render(message, options = {}) {
        const incoming = String(message.sender_type || '') === 'contact';
        const status = String(options.statusOverride || message.status || 'queued').toLowerCase();
        const bubbleClasses = ['wa-bubble', incoming ? 'incoming' : 'outgoing'];
        const rowClasses = ['wa-msg-row', incoming ? 'incoming' : 'outgoing', options.rowClass || ''];
        const updatedAt = message.updated_at || message.sent_at || message.created_at || null;
        const timeLabel = this.formatTime(message.created_at || message.sent_at || message.updated_at);
        const body = this.escapeHtml(message.body || '[No text body]');
        const optimisticClass = options.optimistic ? 'is-optimistic' : '';
        const failedClass = status === 'failed' ? 'is-failed' : '';
        const rowId = message.id ? `data-message-id="${message.id}"` : '';
        const retryButton = !incoming && status === 'failed'
            ? `<button type="button" class="wa-retry-btn" data-wa-retry="1" data-wa-body="${this.escapeAttr(message.body || '')}"><i class="bi bi-arrow-clockwise"></i><span>Retry</span></button>`
            : '';

        return `
            <div class="${this.filterClasses(rowClasses).join(' ')} ${optimisticClass} ${failedClass}" ${rowId}
                 data-message-status="${this.escapeAttr(status)}"
                 data-message-updated-at="${this.escapeAttr(updatedAt || '')}"
                 data-message-body="${this.escapeAttr(message.body || '')}">
                <div class="${bubbleClasses.join(' ')}">
                    <div class="wa-bubble-text">${this.nl2br(body)}</div>
                    <div class="wa-meta">
                        <span>${timeLabel}</span>
                        <span class="d-inline-flex align-items-center gap-2">
                            ${retryButton}
                            ${incoming ? '' : `<span data-wa-status-slot>${renderStatusIcon(status)}</span>`}
                        </span>
                    </div>
                </div>
            </div>
        `;
    }

    updateElement(element, message, options = {}) {
        if (!element) {
            return;
        }

        const incoming = String(message.sender_type || '') === 'contact';
        const status = String(options.statusOverride || message.status || 'queued').toLowerCase();
        const statusSlot = element.querySelector('.wa-meta .d-inline-flex');
        const textSlot = element.querySelector('.wa-bubble-text');

        element.dataset.messageStatus = status;
        if (message.updated_at) {
            element.dataset.messageUpdatedAt = message.updated_at;
        }

        if (message.body && textSlot) {
            textSlot.innerHTML = this.nl2br(this.escapeHtml(message.body));
        }

        if (statusSlot && !incoming) {
            const retryButton = status === 'failed'
                ? `<button type="button" class="wa-retry-btn" data-wa-retry="1" data-wa-body="${this.escapeAttr(message.body || '')}"><i class="bi bi-arrow-clockwise"></i><span>Retry</span></button>`
                : '';

            statusSlot.innerHTML = `${retryButton}<span data-wa-status-slot>${renderStatusIcon(status)}</span>`;
        }

        element.classList.remove('is-optimistic');
        element.classList.toggle('is-failed', status === 'failed');
        element.classList.add('message-flash');
        window.setTimeout(() => element.classList.remove('message-flash'), 650);
    }

    escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    escapeAttr(value) {
        return this.escapeHtml(value).replace(/\n/g, '&#10;');
    }

    nl2br(value) {
        return String(value || '').replace(/\n/g, '<br>');
    }

    formatTime(value) {
        if (!value) {
            return '';
        }

        const parsed = new Date(value);
        if (Number.isNaN(parsed.getTime())) {
            return '';
        }

        return parsed.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    filterClasses(classes) {
        return classes.filter((item) => String(item || '').trim().length > 0);
    }
}