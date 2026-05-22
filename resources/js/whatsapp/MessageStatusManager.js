import { renderStatusIcon } from './StatusRenderer.js';

export default class MessageStatusManager {
    constructor(options = {}) {
        this.state = options.state;
        this.listElement = options.listElement;
        this.intervalMs = Number(options.intervalMs || 3000);
        this.timer = null;
        this.pending = false;
        this.visible = !document.hidden;
        this.paused = false;
        this.started = false;
    }

    start() {
        if (!this.state?.conversationId || !this.state?.statusPollUrl || !this.listElement) {
            return;
        }

        if (!this.started) {
            document.addEventListener('visibilitychange', () => {
                this.visible = !document.hidden;
                if (this.visible && !this.paused) {
                    this.loadStatusUpdates({ force: true });
                }
            });
            this.started = true;
        }

        this.paused = false;
        this.schedule();
        this.loadStatusUpdates({ force: true });
    }

    stop() {
        if (this.timer) {
            window.clearInterval(this.timer);
            this.timer = null;
        }
    }

    setPaused(paused) {
        this.paused = Boolean(paused);
        if (this.paused) {
            this.stop();
            return;
        }

        this.schedule();
        this.loadStatusUpdates({ force: true });
    }

    schedule() {
        this.stop();
        this.timer = window.setInterval(() => {
            if (this.visible && !this.paused) {
                this.loadStatusUpdates();
            }
        }, this.intervalMs);
    }

    async loadStatusUpdates() {
        if (this.pending || this.paused || !this.state?.conversationId || !this.state?.statusPollUrl) {
            return;
        }

        this.pending = true;

        try {
            const params = new URLSearchParams({
                conversation_id: String(this.state.conversationId),
                since: this.state.statusCursor,
            });

            const response = await fetch(`${this.state.statusPollUrl}?${params.toString()}`, {
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

            const statuses = Array.isArray(payload.statuses) ? payload.statuses : [];
            statuses.forEach((item) => this.applyStatusPatch(item));

            const latestCursor = statuses
                .map((item) => item.updated_at || payload.server_time)
                .filter(Boolean)
                .pop();

            if (latestCursor) {
                this.state.updateStatusCursor(latestCursor);
            } else if (payload.server_time) {
                this.state.updateStatusCursor(payload.server_time);
            }
        } catch (error) {
            // Silent fallback for shared-hosting compatibility.
        } finally {
            this.pending = false;
        }
    }

    applyStatusPatch(item) {
        const messageId = item?.message_id;
        const status = String(item?.status || '').toLowerCase();
        if (!messageId || !status) {
            return;
        }

        const row = this.listElement.querySelector(`[data-message-id="${messageId}"]`);
        if (!row) {
            return;
        }

        const currentStatus = String(row.dataset.messageStatus || '').toLowerCase();
        if (currentStatus === status) {
            return;
        }

        row.dataset.messageStatus = status;
        if (item.updated_at) {
            row.dataset.messageUpdatedAt = item.updated_at;
        }

        const iconSlot = row.querySelector('[data-wa-status-slot]');
        if (!iconSlot) {
            return;
        }

        iconSlot.classList.add('wa-status-transition');
        iconSlot.innerHTML = renderStatusIcon(status);
        window.setTimeout(() => iconSlot.classList.remove('wa-status-transition'), 260);
    }
}
