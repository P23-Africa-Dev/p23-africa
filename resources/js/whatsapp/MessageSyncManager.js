export default class MessageSyncManager {
    constructor(options = {}) {
        this.state = options.state;
        this.renderer = options.renderer;
        this.scrollManager = options.scrollManager;
        this.listElement = options.listElement;
        this.emptyStateElement = options.emptyStateElement || null;
        this.unreadBadge = options.unreadBadge || null;
        this.headerMeta = options.headerMeta || {};
        this.listActiveItem = options.listActiveItem || null;
        this.timer = null;
        this.pending = false;
        this.visible = !document.hidden;
        this.started = false;
        this.paused = false;
    }

    start() {
        if (!this.state?.conversationId || !this.listElement) {
            return;
        }

        if (!this.started) {
            this.bindVisibilityWatcher();
            this.bindScrollWatcher();
            this.bindRetryActions();
            this.started = true;
        }

        this.paused = false;
        this.schedule();
        this.loadNewMessages({ force: true });
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
        this.loadNewMessages({ force: true });
    }

    schedule() {
        this.stop();
        this.timer = window.setInterval(() => {
            if (this.visible && !this.paused) {
                this.loadNewMessages();
            }
        }, this.state.pollIntervalMs);
    }

    bindVisibilityWatcher() {
        document.addEventListener('visibilitychange', () => {
            this.visible = !document.hidden;
            if (this.visible && !this.paused) {
                this.loadNewMessages({ force: true });
            }
        });
    }

    bindScrollWatcher() {
        if (!this.scrollManager?.container) {
            return;
        }

        this.scrollManager.container.addEventListener('scroll', () => {
            this.scrollManager.syncButtonVisibility();
        }, { passive: true });
    }

    bindRetryActions() {
        if (!this.listElement) {
            return;
        }

        this.listElement.addEventListener('click', (event) => {
            const button = event.target.closest('[data-wa-retry]');
            if (!button) {
                return;
            }

            const body = button.getAttribute('data-wa-body') || '';
            if (!body.trim()) {
                return;
            }

            const form = document.querySelector('[data-wa-send-form]');
            if (form) {
                const bodyField = form.querySelector('[data-wa-message-body]');
                if (bodyField) {
                    bodyField.value = body;
                }
                form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            }
        });
    }

    async loadNewMessages(options = {}) {
        if (this.pending || this.paused || !this.state?.pollUrl || !this.state?.conversationId) {
            return;
        }

        this.pending = true;

        try {
            const params = new URLSearchParams({
                conversation_id: String(this.state.conversationId),
                scope: 'messages',
                since: this.state.messageCursor,
            });

            const response = await fetch(`${this.state.pollUrl}?${params.toString()}`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            if (!data.success) {
                return;
            }

            this.updateUnreadBadge(data.unread_total);
            this.updateConversationHeader(data.conversation);

            const messages = Array.isArray(data.messages) ? data.messages : [];
            if (messages.length > 0) {
                this.appendIncoming(messages);
                const latestCursor = messages
                    .map((message) => message.updated_at || message.created_at || data.server_time)
                    .filter(Boolean)
                    .pop();

                if (latestCursor) {
                    this.state.updateMessageCursor(latestCursor);
                }
            } else if (data.server_time) {
                this.state.updateMessageCursor(data.server_time);
            }
        } catch (error) {
            // Silent polling fallback by design on shared hosting.
        } finally {
            this.pending = false;
        }
    }

    appendIncoming(messages) {
        if (!Array.isArray(messages) || !this.listElement) {
            return;
        }

        const shouldStickToBottom = this.scrollManager?.isNearBottom() ?? true;
        const fragment = document.createDocumentFragment();
        let appendedCount = 0;

        messages.forEach((message) => {
            const messageId = Number(message.id);

            if (this.state.hasMessage(messageId)) {
                this.patchExistingMessage(message);
                return;
            }

            this.state.addMessage(messageId);
            const html = this.renderer.render(message);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const element = wrapper.firstElementChild;

            if (element) {
                fragment.appendChild(element);
                appendedCount += 1;
            }
        });

        if (appendedCount > 0) {
            if (this.emptyStateElement) {
                this.emptyStateElement.remove();
                this.emptyStateElement = null;
            }

            this.listElement.appendChild(fragment);
            this.animateNewContent();

            if (shouldStickToBottom) {
                this.scrollManager?.scrollToBottom('smooth');
            } else {
                this.scrollManager?.showButton();
            }
        }
    }

    patchExistingMessage(message) {
        const row = this.listElement.querySelector(`[data-message-id="${message.id}"]`);
        if (!row) {
            return;
        }

        this.renderer.updateElement(row, message);
    }

    updateUnreadBadge(count) {
        if (!this.unreadBadge) {
            return;
        }

        const unread = Number(count || 0);
        this.unreadBadge.textContent = `Unread: ${unread}`;
        this.unreadBadge.classList.toggle('d-none', unread <= 0);
        this.state.updateUnreadTotal(unread);
    }

    updateConversationHeader(conversation) {
        if (!conversation) {
            return;
        }

        const statusBadge = document.querySelector('[data-wa-conversation-status-badge]');
        if (statusBadge) {
            statusBadge.textContent = conversation.status ? conversation.status.charAt(0).toUpperCase() + conversation.status.slice(1) : '';
            statusBadge.className = `badge ${this.statusBadgeClass(conversation.status)}`;
        }

        const updatedAt = document.querySelector('[data-wa-conversation-updated-at]');
        if (updatedAt && conversation.updated_at) {
            updatedAt.textContent = this.relativeTime(conversation.updated_at);
        }

        const lastMessage = document.querySelector('[data-wa-conversation-last-message]');
        if (lastMessage && conversation.last_message) {
            lastMessage.textContent = conversation.last_message;
        }
    }

    animateNewContent() {
        this.listElement.querySelectorAll('.message-flash').forEach((item) => {
            item.classList.remove('message-flash');
        });
    }

    statusBadgeClass(status) {
        switch (String(status || '').toLowerCase()) {
            case 'open':
                return 'bg-success';
            case 'pending':
                return 'bg-warning text-dark';
            default:
                return 'bg-secondary';
        }
    }

    relativeTime(value) {
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return '';
        }

        return date.toLocaleString([], {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }
}