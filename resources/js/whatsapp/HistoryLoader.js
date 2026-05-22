export default class HistoryLoader {
    constructor(options = {}) {
        this.listElement = options.listElement || null;
        this.renderer = options.renderer;
        this.state = options.state;
        this.historyUrl = options.historyUrl || '';
        this.emptyStateElement = options.emptyStateElement || null;
        this.loadingElement = options.loadingElement || null;
        this.threshold = Number(options.threshold || 72);
        this.limit = Number(options.limit || 30);
        this.pending = false;
        this.hasMore = true;
        this.oldestMessageId = options.oldestMessageId || this.detectOldestMessageId();
        this.debounceTimer = null;
    }

    start() {
        if (!this.listElement || !this.historyUrl || !this.state?.conversationId) {
            return;
        }

        this.listElement.addEventListener('scroll', () => {
            this.detectTopThreshold();
        }, { passive: true });
    }

    detectOldestMessageId() {
        if (!this.listElement) {
            return null;
        }

        const rows = this.listElement.querySelectorAll('[data-message-id]');
        if (rows.length === 0) {
            return null;
        }

        const first = rows[0]?.getAttribute('data-message-id');
        const parsed = Number(first);

        return Number.isFinite(parsed) ? parsed : null;
    }

    detectTopThreshold() {
        if (!this.listElement || this.pending || !this.hasMore) {
            return;
        }

        if (this.listElement.scrollTop > this.threshold) {
            return;
        }

        if (this.debounceTimer) {
            window.clearTimeout(this.debounceTimer);
        }

        this.debounceTimer = window.setTimeout(() => {
            void this.loadOlderMessages();
        }, 140);
    }

    async loadOlderMessages() {
        if (this.pending || !this.hasMore || !this.listElement || !this.historyUrl) {
            return;
        }

        this.pending = true;
        this.setLoading(true);

        try {
            const params = new URLSearchParams({ limit: String(this.limit) });
            if (this.oldestMessageId) {
                params.set('before_message_id', String(this.oldestMessageId));
            }

            const response = await fetch(`${this.historyUrl}?${params.toString()}`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            if (!payload.success) {
                return;
            }

            const olderMessages = Array.isArray(payload.messages) ? payload.messages : [];
            this.hasMore = Boolean(payload.has_more);

            if (olderMessages.length === 0) {
                return;
            }

            this.appendOlderMessages(olderMessages);

            if (payload.oldest_message_id) {
                this.oldestMessageId = Number(payload.oldest_message_id);
            } else {
                this.oldestMessageId = this.detectOldestMessageId();
            }
        } catch (error) {
            // silent fallback for shared-hosting operation
        } finally {
            this.setLoading(false);
            this.pending = false;
        }
    }

    setLoading(loading) {
        if (!this.loadingElement) {
            return;
        }

        this.loadingElement.classList.toggle('d-none', !loading);
    }

    preserveScrollPosition(callback) {
        if (!this.listElement) {
            callback();
            return;
        }

        const beforeHeight = this.listElement.scrollHeight;
        const beforeTop = this.listElement.scrollTop;

        callback();

        const afterHeight = this.listElement.scrollHeight;
        const delta = afterHeight - beforeHeight;
        this.listElement.scrollTop = beforeTop + delta;
    }

    appendOlderMessages(messages) {
        if (!Array.isArray(messages) || messages.length === 0 || !this.listElement) {
            return;
        }

        const fragment = document.createDocumentFragment();

        messages.forEach((message) => {
            const messageId = Number(message.id);
            if (this.state?.hasMessage(messageId)) {
                return;
            }

            this.state?.addMessage(messageId);

            const html = this.renderer.render(message, { rowClass: 'wa-history-row' });
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const element = wrapper.firstElementChild;

            if (element) {
                fragment.appendChild(element);
            }
        });

        if (!fragment.firstChild) {
            return;
        }

        this.preserveScrollPosition(() => {
            if (this.emptyStateElement) {
                this.emptyStateElement.remove();
                this.emptyStateElement = null;
            }

            this.listElement.prepend(fragment);
        });
    }
}
