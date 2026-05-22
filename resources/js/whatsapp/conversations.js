import ConversationState from './ConversationState.js';
import ScrollManager from './ScrollManager.js';
import MessageRenderer from './MessageRenderer.js';
import MessageSyncManager from './MessageSyncManager.js';
import StatusPollManager from './StatusPollManager.js';
import HistoryLoader from './HistoryLoader.js';
import ConversationScrollManager from './ConversationScrollManager.js';

function escapeHtml(value) {
    return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function initAccordionState() {
    document.querySelectorAll('[data-wa-accordion]').forEach((details) => {
        const key = details.getAttribute('data-wa-accordion');
        if (!key) {
            return;
        }

        const saved = window.localStorage.getItem(`wa-accordion:${key}`);
        if (saved === 'open') {
            details.open = true;
        }

        details.addEventListener('toggle', () => {
            window.localStorage.setItem(`wa-accordion:${key}`, details.open ? 'open' : 'closed');
        });
    });
}

function autoResizeTextarea(textarea) {
    if (!textarea) {
        return;
    }

    const resize = () => {
        textarea.style.height = 'auto';
        textarea.style.height = `${Math.min(textarea.scrollHeight, 220)}px`;
    };

    resize();
    textarea.addEventListener('input', resize);
}

function initDebouncedSearch(form) {
    if (!form) {
        return;
    }

    const input = form.querySelector('[data-wa-search-input]');
    if (!input) {
        return;
    }

    let timer = null;
    input.addEventListener('input', () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(() => {
            form.requestSubmit();
        }, 450);
    });
}

async function submitAjaxForm(form, options = {}) {
    const response = await fetch(form.action, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': options.csrfToken,
        },
        body: new FormData(form),
    });

    const data = await response.json().catch(() => ({}));
    if (!response.ok || !data.success) {
        throw new Error(data.message || 'Request failed.');
    }

    return data;
}

function createDebugReporter(root, csrfToken) {
    const enabled = root?.getAttribute('data-wa-debug-enabled') === '1';
    const debugUrl = root?.getAttribute('data-wa-debug-url') || '';

    return async (event, context = {}) => {
        if (!enabled || !debugUrl) {
            return;
        }

        try {
            await fetch(debugUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ event, context }),
                keepalive: true,
            });
        } catch (error) {
            // debug transport is best-effort only
        }
    };
}

function updateHistoryConversation(value) {
    const url = new URL(window.location.href);

    if (value === null || value === undefined || value === '') {
        url.searchParams.delete('conversation');
    } else {
        url.searchParams.set('conversation', value);
    }

    window.history.replaceState({}, '', url.toString());
}

function setItemActive(item, active) {
    if (!item) {
        return;
    }

    item.classList.toggle('active', Boolean(active));
}

function initMessaging() {
    const root = document.querySelector('[data-wa-root]');
    if (!root) {
        return;
    }

    const listElement = document.querySelector('[data-wa-message-list]');
    const conversationScrollElement = document.querySelector('[data-wa-conversation-scroll]');
    const messageScrollElement = document.querySelector('[data-wa-message-scroll]');
    const historyLoadingElement = document.querySelector('[data-wa-history-loading]');
    const emptyStateElement = document.querySelector('[data-wa-empty-state]');
    const threadContent = document.querySelector('[data-wa-thread-content]');
    const emptyChatWrap = document.querySelector('[data-wa-empty-chat-wrap]');
    const closeChatButton = document.querySelector('[data-wa-close-chat]');
    const reopenChatButton = document.querySelector('[data-wa-reopen-chat]');
    const rightPanel = document.querySelector('[data-wa-right-panel]');
    const newestButton = document.querySelector('[data-wa-scroll-newest]');
    const unreadBadge = document.querySelector('[data-wa-unread-total]');
    const sendForm = document.querySelector('[data-wa-send-form]');
    const noteForm = document.querySelector('[data-wa-note-form]');
    const conversationForms = document.querySelectorAll('[data-wa-ajax-conversation-form]');
    const searchForm = document.querySelector('[data-wa-search-form]');
    const textarea = document.querySelector('[data-wa-message-body]');
    const conversationId = Number(root?.getAttribute('data-wa-conversation-id') || 0) || null;
    const pollUrl = root?.getAttribute('data-wa-poll-url') || '';
    const statusPollUrl = root?.getAttribute('data-wa-status-poll-url') || '';
    const statusPollIntervalMs = Number(root?.getAttribute('data-wa-status-poll-interval') || 3000);
    const historyUrl = listElement?.getAttribute('data-wa-history-url') || '';
    const historyLimit = Number(listElement?.getAttribute('data-wa-history-limit') || 30);
    const sidebarPollUrl = root?.getAttribute('data-wa-sidebar-poll-url') || pollUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const pollIntervalRaw = Number(root?.getAttribute('data-wa-poll-interval') || 5);
    const pollIntervalMs = pollIntervalRaw > 100 ? pollIntervalRaw : pollIntervalRaw * 1000;
    const initialCursor = root?.getAttribute('data-wa-poll-cursor') || new Date().toISOString();
    const statusIntervalMs = Math.max(1500, statusPollIntervalMs);
    const sidebarIntervalMs = Math.max(6000, pollIntervalMs * 2);
    const knownMessageIds = Array.from(document.querySelectorAll('[data-message-id]'))
        .map((item) => Number(item.getAttribute('data-message-id')))
        .filter((id) => Number.isFinite(id));
    const currentConversationItem = conversationId
        ? document.querySelector(`[data-wa-conversation-item="${conversationId}"]`)
        : null;
    const debug = createDebugReporter(root, csrfToken);

    const storagePrefix = `wa-chat:${conversationId || 'none'}`;
    const draftKey = `${storagePrefix}:draft`;
    const scrollKey = `${storagePrefix}:scroll`;
    const closedKey = `${storagePrefix}:closed`;
    const activeConversationKey = 'wa-chat:active-conversation';
    const activeConversationHrefKey = 'wa-chat:active-conversation-href';

    const state = new ConversationState({
        conversationId,
        pollUrl,
        statusPollUrl,
        sidebarPollUrl,
        csrfToken,
        pollIntervalMs,
        messageCursor: initialCursor,
        statusCursor: initialCursor,
        sidebarCursor: initialCursor,
        knownMessageIds,
        unreadTotal: Number(unreadBadge?.dataset.count || 0),
    });

    const renderer = new MessageRenderer({});
    const scrollManager = new ScrollManager(listElement, { newestButton });
    const syncManager = new MessageSyncManager({
        state,
        renderer,
        scrollManager,
        listElement,
        emptyStateElement,
        unreadBadge,
    });
    const statusManager = new StatusPollManager({
        state,
        listElement,
        intervalMs: statusIntervalMs,
    });
    const historyLoader = new HistoryLoader({
        listElement,
        renderer,
        state,
        historyUrl,
        emptyStateElement,
        loadingElement: historyLoadingElement,
        limit: historyLimit,
        threshold: 56,
    });
    const isolatedScrollManager = new ConversationScrollManager({
        panels: [conversationScrollElement, messageScrollElement],
    });

    const updateSidebarConversationItem = (conversation) => {
        if (!conversation?.id) {
            return;
        }

        const item = document.querySelector(`[data-wa-conversation-item="${conversation.id}"]`);
        if (!item) {
            return;
        }

        const unreadNode = item.querySelector('[data-wa-sidebar-unread]');
        if (unreadNode) {
            const unread = Number(conversation.unread_count || 0);
            unreadNode.textContent = String(unread);
            unreadNode.classList.toggle('d-none', unread <= 0);
        }

        const lastMessageNode = item.querySelector('[data-wa-sidebar-last-message]');
        if (lastMessageNode && conversation.last_message) {
            lastMessageNode.textContent = String(conversation.last_message).slice(0, 140);
        }

        const statusNode = item.querySelector('[data-wa-sidebar-status]');
        if (statusNode && conversation.status) {
            statusNode.textContent = conversation.status.charAt(0).toUpperCase() + conversation.status.slice(1);
            statusNode.className = `badge ${conversation.status === 'open' ? 'bg-success' : (conversation.status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')}`;
        }

        const updatedNode = item.querySelector('[data-wa-sidebar-updated-at]');
        if (updatedNode && conversation.updated_at) {
            const dt = new Date(conversation.updated_at);
            if (!Number.isNaN(dt.getTime())) {
                updatedNode.textContent = dt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }
    };

    const saveDraft = () => {
        if (!textarea || !conversationId) {
            return;
        }

        window.localStorage.setItem(draftKey, textarea.value || '');
    };

    const restoreDraft = () => {
        if (!textarea || !conversationId) {
            return;
        }

        const saved = window.localStorage.getItem(draftKey);
        if (saved !== null) {
            textarea.value = saved;
            textarea.dispatchEvent(new Event('input'));
        }
    };

    const saveScroll = () => {
        if (!listElement || !conversationId) {
            return;
        }

        window.localStorage.setItem(scrollKey, String(listElement.scrollTop || 0));
    };

    const restoreScroll = () => {
        if (!listElement || !conversationId) {
            return;
        }

        const saved = Number(window.localStorage.getItem(scrollKey) || 'NaN');
        if (Number.isFinite(saved)) {
            listElement.scrollTop = saved;
        }
    };

    const persistActiveConversation = () => {
        if (!conversationId || !currentConversationItem) {
            return;
        }

        window.localStorage.setItem(activeConversationKey, String(conversationId));
        window.localStorage.setItem(activeConversationHrefKey, currentConversationItem.href);
    };

    const toggleChatClosedState = (closed) => {
        const isClosed = Boolean(closed);
        state.isChatClosed = isClosed;

        if (threadContent) {
            threadContent.classList.toggle('d-none', isClosed);
        }

        if (emptyChatWrap) {
            emptyChatWrap.classList.toggle('d-none', !isClosed);
        }

        if (rightPanel) {
            rightPanel.classList.toggle('d-none', isClosed);
        }

        syncManager.setPaused(isClosed);
        statusManager.setPaused(isClosed);

        setItemActive(currentConversationItem, !isClosed);

        if (isClosed) {
            updateHistoryConversation('none');
        } else if (conversationId) {
            updateHistoryConversation(String(conversationId));
        }

        if (conversationId) {
            window.localStorage.setItem(closedKey, isClosed ? '1' : '0');
        }
    };

    const startSidebarPolling = () => {
        if (!state.sidebarPollUrl) {
            return;
        }

        let pending = false;
        let visible = !document.hidden;

        const loadSidebarUpdates = async () => {
            if (pending || !visible) {
                return;
            }

            pending = true;
            try {
                const params = new URLSearchParams({
                    scope: 'conversations',
                    since: state.sidebarCursor,
                });

                const response = await fetch(`${state.sidebarPollUrl}?${params.toString()}`, {
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

                if (unreadBadge) {
                    const unread = Number(payload.unread_total || 0);
                    unreadBadge.textContent = `Unread: ${unread}`;
                    unreadBadge.classList.toggle('d-none', unread <= 0);
                }

                const conversations = Array.isArray(payload.conversations) ? payload.conversations : [];
                conversations.forEach((conversation) => updateSidebarConversationItem(conversation));

                if (payload.server_time) {
                    state.updateSidebarCursor(payload.server_time);
                }

                void debug('sidebar_poll', {
                    count: conversations.length,
                    unread_total: Number(payload.unread_total || 0),
                });
            } catch (error) {
                // silent fallback
            } finally {
                pending = false;
            }
        };

        document.addEventListener('visibilitychange', () => {
            visible = !document.hidden;
            if (visible) {
                loadSidebarUpdates();
            }
        });

        window.setInterval(loadSidebarUpdates, sidebarIntervalMs);
        loadSidebarUpdates();
    };

    if (listElement) {
        scrollManager.syncButtonVisibility();
        syncManager.start();
        statusManager.start();
        historyLoader.start();
        window.setTimeout(() => scrollManager.scrollToBottom('auto'), 0);
        listElement.addEventListener('scroll', saveScroll, { passive: true });
    }

    isolatedScrollManager.start();

    startSidebarPolling();
    persistActiveConversation();
    void debug('frontend_registration', {
        component: 'whatsapp.conversations',
        conversation_id: conversationId,
        has_list: Boolean(listElement),
        status_poll_interval_ms: statusIntervalMs,
    });
    void debug('asset_loading', {
        asset: 'resources/js/whatsapp/conversations.js',
    });

    if (conversationId) {
        restoreDraft();
        const isInitiallyClosed = window.localStorage.getItem(closedKey) === '1';
        if (isInitiallyClosed) {
            toggleChatClosedState(true);
        } else {
            restoreScroll();
            setItemActive(currentConversationItem, true);
        }
    }

    if (textarea) {
        autoResizeTextarea(textarea);
        textarea.addEventListener('input', saveDraft);

        textarea.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendForm?.requestSubmit();
            }
        });
    }

    const templatePicker = document.getElementById('waTemplatePicker');
    if (templatePicker && textarea) {
        templatePicker.addEventListener('change', function () {
            if (!this.value) {
                return;
            }

            try {
                const json = JSON.parse(this.value);
                const text = (json.components || [])
                    .filter((component) => component.type === 'BODY')
                    .map((component) => component.text || '')
                    .join('\n') || (json.body || '');

                if (text) {
                    textarea.value = text;
                    textarea.dispatchEvent(new Event('input'));
                }
            } catch (error) {
                // ignore malformed template JSON
            }
        });
    }

    if (sendForm) {
        sendForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const bodyField = sendForm.querySelector('[data-wa-message-body]');
            const body = bodyField?.value.trim() || '';
            if (!body || state.isSending) {
                return;
            }

            const sendButton = sendForm.querySelector('[data-wa-send-button]');
            const tempId = `local-${Date.now()}`;
            const optimisticMessage = {
                id: tempId,
                sender_type: 'admin',
                type: 'text',
                body,
                status: 'sending',
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
            };

            state.isSending = true;
            if (sendButton) {
                sendButton.disabled = true;
                sendButton.dataset.originalHtml = sendButton.innerHTML;
                sendButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...';
            }

            if (listElement) {
                const html = renderer.render(optimisticMessage, { optimistic: true, statusOverride: 'sending' });
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html.trim();
                const element = wrapper.firstElementChild;
                if (element) {
                    listElement.appendChild(element);
                    state.addMessage(tempId);
                    scrollManager.scrollToBottom('smooth');
                }
            }

            try {
                const data = await submitAjaxForm(sendForm, { csrfToken });
                if (bodyField) {
                    bodyField.value = '';
                    bodyField.dispatchEvent(new Event('input'));
                }

                const responseMessage = data.data || {};
                const row = listElement?.querySelector(`[data-message-id="${tempId}"]`);

                if (row) {
                    const finalized = {
                        id: responseMessage.id || tempId,
                        sender_type: responseMessage.sender_type || 'admin',
                        type: responseMessage.type || 'text',
                        body: responseMessage.body || body,
                        status: responseMessage.status || 'queued',
                        created_at: responseMessage.created_at || new Date().toISOString(),
                        updated_at: responseMessage.updated_at || responseMessage.created_at || new Date().toISOString(),
                    };

                    row.setAttribute('data-message-id', String(finalized.id));
                    state.addMessage(finalized.id);
                    renderer.updateElement(row, finalized, { statusOverride: finalized.status });
                }

                if (responseMessage.created_at) {
                    state.updateMessageCursor(responseMessage.created_at);
                    state.updateStatusCursor(responseMessage.created_at);
                }
            } catch (error) {
                const row = listElement?.querySelector(`[data-message-id="${tempId}"]`);
                if (row) {
                    renderer.updateElement(row, {
                        id: tempId,
                        sender_type: 'admin',
                        body,
                        status: 'failed',
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString(),
                    }, { statusOverride: 'failed' });
                }
            } finally {
                state.isSending = false;
                if (sendButton) {
                    sendButton.disabled = false;
                    sendButton.innerHTML = sendButton.dataset.originalHtml || '<i class="bi bi-send me-1"></i>Send Message';
                }
                saveDraft();
            }
        });
    }

    if (noteForm) {
        noteForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const textarea = noteForm.querySelector('textarea[name="note"]');
            const note = textarea?.value.trim() || '';
            if (!note) {
                return;
            }

            try {
                const data = await submitAjaxForm(noteForm, { csrfToken });
                const list = document.querySelector('[data-wa-notes-list]');
                if (list) {
                    const item = document.createElement('div');
                    item.className = 'mb-2 pb-2 border-bottom';
                    item.innerHTML = `
                        <div class="small fw-semibold">${escapeHtml(data.data?.admin_name || 'Admin')}</div>
                        <div class="small">${escapeHtml(data.data?.note || note)}</div>
                    `;
                    list.prepend(item);
                }

                if (textarea) {
                    textarea.value = '';
                }
            } catch (error) {
                // Keep UX quiet; the form will still submit normally if needed.
            }
        });
    }

    conversationForms.forEach((form) => {
        const selects = form.querySelectorAll('select');
        selects.forEach((select) => {
            select.addEventListener('change', () => form.requestSubmit());
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            try {
                const data = await submitAjaxForm(form, { csrfToken });
                const patch = data.data || {};
                const badge = document.querySelector('[data-wa-conversation-status-badge]');
                if (badge && patch.status) {
                    badge.textContent = patch.status.charAt(0).toUpperCase() + patch.status.slice(1);
                    badge.className = `badge ${patch.status === 'open' ? 'bg-success' : (patch.status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')}`;
                }
            } catch (error) {
                // No-op: preserve current page state.
            }
        });
    });

    initAccordionState();
    initDebouncedSearch(searchForm);

    if (newestButton) {
        newestButton.addEventListener('click', () => {
            scrollManager.scrollToBottom('smooth');
            saveScroll();
        });
    }

    if (closeChatButton) {
        closeChatButton.addEventListener('click', () => {
            persistActiveConversation();
            saveDraft();
            saveScroll();
            toggleChatClosedState(true);
            void debug('close_chat', {
                action: 'close',
                conversation_id: conversationId,
            });
        });
    }

    if (reopenChatButton) {
        reopenChatButton.addEventListener('click', () => {
            const savedHref = window.localStorage.getItem(activeConversationHrefKey);

            if (!conversationId && savedHref) {
                window.location.assign(savedHref);
                return;
            }

            toggleChatClosedState(false);
            restoreDraft();
            restoreScroll();
            textarea?.focus();
            void debug('close_chat', {
                action: 'reopen',
                conversation_id: conversationId,
            });
        });
    }

    document.querySelectorAll('[data-wa-conversation-item]').forEach((item) => {
        item.addEventListener('click', () => {
            const id = item.getAttribute('data-wa-conversation-item');
            if (id) {
                window.localStorage.setItem(`wa-chat:${id}:closed`, '0');
                window.localStorage.setItem(activeConversationKey, id);
                window.localStorage.setItem(activeConversationHrefKey, item.href);
            }
        });
    });
}

export function bootWhatsAppConversations() {
    if (window.__waConversationsBooted) {
        return;
    }

    window.__waConversationsBooted = true;
    initMessaging();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootWhatsAppConversations, { once: true });
} else {
    bootWhatsAppConversations();
}
