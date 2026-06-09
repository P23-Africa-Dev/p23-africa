import ConversationState from './ConversationState.js';
import ScrollManager from './ScrollManager.js';
import MessageRenderer from './MessageRenderer.js';
import MessageSyncManager from './MessageSyncManager.js';
import StatusPollManager from './StatusPollManager.js';
import HistoryLoader from './HistoryLoader.js';
import ConversationScrollManager from './ConversationScrollManager.js';
import { bootActionMenuManager } from './ActionMenuManager.js';

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

async function postJson(url, payload, csrfToken) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(payload),
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
    const parentCard = item.closest('.wa-conversation-item');
    if (parentCard) {
        parentCard.classList.toggle('active', Boolean(active));
    }
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
    const newChatForm = document.querySelector('[data-wa-new-chat-form]');
    const saveContactForm = document.querySelector('[data-wa-save-contact-form]');
    const searchForm = document.querySelector('[data-wa-search-form]');
    const textarea = document.querySelector('[data-wa-message-body]');
    const conversationId = Number(root?.getAttribute('data-wa-conversation-id') || 0) || null;
    const pollUrl = root?.getAttribute('data-wa-poll-url') || '';
    const statusPollUrl = root?.getAttribute('data-wa-status-poll-url') || '';
    const quickChatValidateUrl = root?.getAttribute('data-wa-quick-chat-validate-url') || '';
    const quickChatTemplatesUrl = root?.getAttribute('data-wa-quick-chat-templates-url') || '';
    const quickChatStartUrl = root?.getAttribute('data-wa-quick-chat-start-url') || '';
    const quickChatStartTemplateUrl = root?.getAttribute('data-wa-quick-chat-start-template-url') || '';
    const saveContactUrl = root?.getAttribute('data-wa-save-contact-url') || '';
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
    const templateModal = document.querySelector('[data-wa-template-modal]');
    const templateSendForm = templateModal?.querySelector('[data-wa-template-send-form]') || null;
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
            statusNode.setAttribute('data-status', conversation.status);
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

    let templateCatalog = [];
    let templateMode = 'conversation';
    let selectedTemplate = null;

    const templateContextBadge = templateModal?.querySelector('[data-wa-template-context]') || null;
    const templateRecipientSummary = templateModal?.querySelector('[data-wa-template-recipient-summary]') || null;
    const templateRecipientWrap = templateModal?.querySelector('[data-wa-template-recipient-wrap]') || null;
    const templateRecipientName = templateModal?.querySelector('[data-wa-template-recipient-name]') || null;
    const templateRecipientPhone = templateModal?.querySelector('[data-wa-template-recipient-phone]') || null;
    const templateRecipientCountryCode = templateModal?.querySelector('[data-wa-template-recipient-country-code]') || null;
    const templateRecipientValidation = templateModal?.querySelector('[data-wa-template-recipient-validation]') || null;
    const templateValidateRecipientBtn = templateModal?.querySelector('[data-wa-template-validate-recipient]') || null;
    const templateSearchInput = templateModal?.querySelector('[data-wa-template-search]') || null;
    const templateListWrap = templateModal?.querySelector('[data-wa-template-list]') || null;
    const templateSelectedWrap = templateModal?.querySelector('[data-wa-template-selected-wrap]') || null;
    const templateEmptyState = templateModal?.querySelector('[data-wa-template-empty-state]') || null;
    const templateSelectedName = templateModal?.querySelector('[data-wa-template-selected-name]') || null;
    const templateSelectedStatus = templateModal?.querySelector('[data-wa-template-selected-status]') || null;
    const templateSelectedCategory = templateModal?.querySelector('[data-wa-template-selected-category]') || null;
    const templateSelectedLanguage = templateModal?.querySelector('[data-wa-template-selected-language]') || null;
    const templateSelectedUpdated = templateModal?.querySelector('[data-wa-template-selected-updated]') || null;
    const templateVariableFields = templateModal?.querySelector('[data-wa-template-variable-fields]') || null;
    const templatePreview = templateModal?.querySelector('[data-wa-template-preview]') || null;
    const templateSendResult = templateModal?.querySelector('[data-wa-template-send-result]') || null;
    const templateSendButton = templateModal?.querySelector('[data-wa-template-send-button]') || null;

    const setTemplateResult = (text, style = 'muted') => {
        if (!templateSendResult) {
            return;
        }

        templateSendResult.textContent = text;
        templateSendResult.className = `small text-${style}`;
    };

    const setRecipientValidation = (text, style = 'muted') => {
        if (!templateRecipientValidation) {
            return;
        }

        templateRecipientValidation.textContent = text;
        templateRecipientValidation.className = `small text-${style}`;
    };

    const renderTemplatePreview = () => {
        if (!templatePreview || !selectedTemplate) {
            return;
        }

        const variables = {};
        templateVariableFields?.querySelectorAll('[data-template-variable-key]').forEach((input) => {
            if (!(input instanceof HTMLInputElement)) {
                return;
            }

            variables[input.getAttribute('data-template-variable-key') || ''] = input.value.trim();
        });

        const type = String(selectedTemplate.variable_type || 'named');
        const source = String(selectedTemplate.preview_template || '');
        const previewText = source.replace(/\{\{\s*([^\}]+?)\s*\}\}/g, (full, raw) => {
            const key = String(raw || '').trim();
            if (!key) {
                return full;
            }

            if (type === 'number') {
                return variables[key] || full;
            }

            const byKey = variables[key];
            const byLower = variables[key.toLowerCase()];
            return byKey || byLower || full;
        });

        templatePreview.textContent = previewText || 'No preview text available for this template.';
    };

    const renderTemplateVariables = () => {
        if (!templateVariableFields || !selectedTemplate) {
            return;
        }

        templateVariableFields.innerHTML = '';
        const variables = Array.isArray(selectedTemplate.variables) ? selectedTemplate.variables : [];

        if (!variables.length) {
            templateVariableFields.innerHTML = '<div class="col-12 text-muted small">This template has no required variables.</div>';
            renderTemplatePreview();
            return;
        }

        variables.forEach((variable) => {
            const key = String(variable.key || '');
            const label = String(variable.label || key || 'Variable');

            const col = document.createElement('div');
            col.className = 'col-12 col-md-6';
            col.innerHTML = `
                <label class="form-label">${escapeHtml(label)}</label>
                <input type="text" class="form-control form-control-sm" data-template-variable-key="${escapeHtml(key)}" placeholder="Enter ${escapeHtml(label.toLowerCase())}">
            `;
            templateVariableFields.appendChild(col);
        });

        templateVariableFields.querySelectorAll('[data-template-variable-key]').forEach((input) => {
            input.addEventListener('input', renderTemplatePreview);
        });

        renderTemplatePreview();
    };

    const selectTemplate = (template) => {
        selectedTemplate = template;

        if (!templateSelectedWrap || !templateEmptyState) {
            return;
        }

        templateSelectedWrap.classList.remove('d-none');
        templateEmptyState.classList.add('d-none');
        templateSendButton?.classList.remove('disabled');
        if (templateSendButton) {
            templateSendButton.disabled = false;
        }

        if (templateSelectedName) {
            templateSelectedName.textContent = String(template.name || '');
        }
        if (templateSelectedStatus) {
            templateSelectedStatus.textContent = String(template.status || 'approved').toUpperCase();
        }
        if (templateSelectedCategory) {
            templateSelectedCategory.textContent = String(template.category || 'utility').toUpperCase();
        }
        if (templateSelectedLanguage) {
            templateSelectedLanguage.textContent = String(template.language || 'en');
        }
        if (templateSelectedUpdated) {
            templateSelectedUpdated.textContent = template.updated_at
                ? new Date(template.updated_at).toLocaleString()
                : '-';
        }

        renderTemplateVariables();
    };

    const renderTemplateList = () => {
        if (!templateListWrap) {
            return;
        }

        const query = String(templateSearchInput?.value || '').trim().toLowerCase();
        const filtered = templateCatalog.filter((template) => {
            const haystack = [template.name, template.category, template.language]
                .map((value) => String(value || '').toLowerCase())
                .join(' ');

            return !query || haystack.includes(query);
        });

        if (!filtered.length) {
            templateListWrap.innerHTML = '<div class="text-muted small">No approved templates found.</div>';
            return;
        }

        templateListWrap.innerHTML = filtered.map((template) => {
            return `
                <button type="button" class="btn btn-sm btn-outline-secondary w-100 text-start mb-1" data-template-pick="${template.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>${escapeHtml(String(template.name || ''))}</strong>
                        <small class="text-muted">${escapeHtml(String(template.language || 'en'))}</small>
                    </div>
                    <div class="small text-muted">${escapeHtml(String(template.category || 'utility').toUpperCase())}</div>
                </button>
            `;
        }).join('');

        templateListWrap.querySelectorAll('[data-template-pick]').forEach((button) => {
            button.addEventListener('click', () => {
                const id = Number(button.getAttribute('data-template-pick'));
                const template = templateCatalog.find((item) => Number(item.id) === id);
                if (template) {
                    selectTemplate(template);
                }
            });
        });
    };

    const loadTemplateCatalog = async () => {
        if (!quickChatTemplatesUrl || templateCatalog.length) {
            renderTemplateList();
            return;
        }

        templateListWrap && (templateListWrap.innerHTML = '<div class="text-muted small">Loading templates...</div>');

        try {
            const response = await fetch(quickChatTemplatesUrl, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const payload = await response.json().catch(() => ({}));
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Unable to load templates.');
            }

            templateCatalog = Array.isArray(payload?.data?.templates) ? payload.data.templates : [];
            renderTemplateList();
        } catch (error) {
            templateListWrap && (templateListWrap.innerHTML = '<div class="text-danger small">Failed to load approved templates.</div>');
        }
    };

    const collectTemplateVariables = () => {
        const variables = {};

        templateVariableFields?.querySelectorAll('[data-template-variable-key]').forEach((input) => {
            if (!(input instanceof HTMLInputElement)) {
                return;
            }

            const key = String(input.getAttribute('data-template-variable-key') || '').trim();
            if (!key) {
                return;
            }

            variables[key] = input.value.trim();
        });

        return variables;
    };

    const validateTemplateInputs = () => {
        if (!selectedTemplate) {
            throw new Error('Select an approved template first.');
        }

        const variables = collectTemplateVariables();
        const required = Array.isArray(selectedTemplate.variables) ? selectedTemplate.variables : [];

        for (const variable of required) {
            const key = String(variable.key || '');
            if (!key) {
                continue;
            }

            if (!String(variables[key] || '').trim()) {
                throw new Error(`Template variable "${variable.label || key}" is required.`);
            }
        }

        return variables;
    };

    const validateOutreachRecipient = async () => {
        const payload = {
            name: String(templateRecipientName?.value || '').trim(),
            phone_number: String(templateRecipientPhone?.value || '').trim(),
            country_code: String(templateRecipientCountryCode?.value || '').trim(),
        };

        if (!payload.phone_number) {
            throw new Error('Phone number is required for first outreach.');
        }

        const data = await postJson(quickChatValidateUrl, payload, csrfToken);
        const status = String(data?.data?.validation_status || '').toLowerCase();
        const isWhatsApp = data?.data?.is_whatsapp === true;

        if (status === 'invalid' || data?.data?.is_whatsapp === false) {
            throw new Error('This number is not registered on WhatsApp.');
        }

        if (status !== 'valid' || !isWhatsApp) {
            throw new Error('Unable to validate recipient. Please retry.');
        }

        setRecipientValidation('Recipient is valid and WhatsApp-enabled.', 'success');

        return payload;
    };

    const appendOptimisticTemplateMessage = (templateName, previewBody) => {
        if (!listElement) {
            return null;
        }

        const tempId = `local-template-${Date.now()}`;
        const optimistic = {
            id: tempId,
            sender_type: 'admin',
            type: 'template',
            body: `Template: ${templateName}${previewBody ? ` - ${previewBody}` : ''}`,
            status: 'sending',
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString(),
        };

        const html = renderer.render(optimistic, { optimistic: true, statusOverride: 'sending' });
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        const element = wrapper.firstElementChild;

        if (!element) {
            return null;
        }

        listElement.appendChild(element);
        state.addMessage(tempId);
        scrollManager.scrollToBottom('smooth');

        return { tempId, element, optimistic };
    };

    const resetTemplateModalState = () => {
        selectedTemplate = null;
        templateSearchInput && (templateSearchInput.value = '');
        templateListWrap && (templateListWrap.innerHTML = '');
        templateSelectedWrap?.classList.add('d-none');
        templateEmptyState?.classList.remove('d-none');
        templateVariableFields && (templateVariableFields.innerHTML = '');
        templatePreview && (templatePreview.textContent = '');
        templateSendButton?.classList.add('disabled');
        if (templateSendButton) {
            templateSendButton.disabled = true;
        }
        setTemplateResult('');
    };

    const openTemplateModal = async (mode = 'conversation') => {
        if (!templateModal || !templateSendForm) {
            return;
        }

        templateMode = mode === 'outreach' ? 'outreach' : 'conversation';
        resetTemplateModalState();

        if (templateContextBadge) {
            templateContextBadge.textContent = templateMode === 'outreach' ? 'First Outreach' : 'Conversation';
        }

        const name = document.querySelector('[data-wa-contact-name]')?.textContent?.trim() || '';
        const phone = document.querySelector('[data-wa-contact-phone]')?.textContent?.trim() || '';

        if (templateMode === 'outreach') {
            templateRecipientWrap?.classList.remove('d-none');
            if (templateRecipientSummary) {
                templateRecipientSummary.textContent = 'Provide recipient details and validate before sending.';
            }

            const chatName = newChatForm?.querySelector('input[name="name"]')?.value?.trim() || '';
            const chatPhone = newChatForm?.querySelector('input[name="phone_number"]')?.value?.trim() || '';

            if (templateRecipientName) {
                templateRecipientName.value = chatName;
            }
            if (templateRecipientPhone) {
                templateRecipientPhone.value = chatPhone;
            }
            setRecipientValidation('');
        } else {
            templateRecipientWrap?.classList.add('d-none');
            if (templateRecipientSummary) {
                templateRecipientSummary.textContent = `${name || 'Contact'} ${phone ? `(${phone})` : ''}`.trim();
            }
        }

        await loadTemplateCatalog();
        renderTemplateList();

        const modalInstance = window.bootstrap?.Modal ? window.bootstrap.Modal.getOrCreateInstance(templateModal) : null;
        modalInstance?.show();
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
    bootActionMenuManager(root, conversationId);
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

    if (sendForm) {
        sendForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const bodyField = sendForm.querySelector('[data-wa-message-body]');
            const body = bodyField?.value.trim() || '';

            const messageTypeField = sendForm.querySelector('[data-wa-message-type]');
            if (messageTypeField) {
                messageTypeField.value = 'text';
            }

            if (!body || state.isSending) {
                return;
            }

            const sendButton = sendForm.querySelector('[data-wa-send-button]');
            const tempId = `local-${Date.now()}`;
            const optimisticBody = body;
            const optimisticMessage = {
                id: tempId,
                sender_type: 'admin',
                type: 'text',
                body: optimisticBody,
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
                        body: responseMessage.body || optimisticBody,
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
                        type: 'text',
                        body: optimisticBody,
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

    if (templateSearchInput) {
        templateSearchInput.addEventListener('input', renderTemplateList);
    }

    templateValidateRecipientBtn?.addEventListener('click', async () => {
        if (!quickChatValidateUrl) {
            return;
        }

        try {
            setRecipientValidation('Validating recipient...', 'secondary');
            await validateOutreachRecipient();
        } catch (error) {
            setRecipientValidation(error instanceof Error ? error.message : 'Unable to validate recipient.', 'danger');
        }
    });

    templateSendForm?.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!selectedTemplate) {
            setTemplateResult('Select an approved template first.', 'warning');
            return;
        }

        const sendButton = templateSendButton;
        if (sendButton) {
            sendButton.disabled = true;
            sendButton.textContent = 'Sending...';
        }

        try {
            const variables = validateTemplateInputs();
            const previewText = String(templatePreview?.textContent || '').trim().slice(0, 180);

            if (templateMode === 'outreach') {
                if (!quickChatStartTemplateUrl) {
                    throw new Error('Template outreach endpoint is not available.');
                }

                const recipient = await validateOutreachRecipient();
                const payload = {
                    ...recipient,
                    template_id: selectedTemplate.id,
                    template_variables: variables,
                };

                const data = await postJson(quickChatStartTemplateUrl, payload, csrfToken);
                const destination = data?.data?.conversation_url;

                if (destination) {
                    window.location.assign(destination);
                    return;
                }

                window.location.reload();
                return;
            }

            if (!conversationId || !sendForm?.action) {
                throw new Error('No active conversation is selected.');
            }

            const optimistic = appendOptimisticTemplateMessage(String(selectedTemplate.name || 'template'), previewText);

            const data = await postJson(sendForm.action, {
                type: 'template',
                template_id: selectedTemplate.id,
                template_variables: variables,
            }, csrfToken);

            const responseMessage = data?.data || {};

            if (optimistic?.element) {
                const finalized = {
                    id: responseMessage.id || optimistic.tempId,
                    sender_type: responseMessage.sender_type || 'admin',
                    type: responseMessage.type || 'template',
                    body: responseMessage.body || optimistic.optimistic.body,
                    status: responseMessage.status || 'queued',
                    created_at: responseMessage.created_at || new Date().toISOString(),
                    updated_at: responseMessage.updated_at || responseMessage.created_at || new Date().toISOString(),
                };

                optimistic.element.setAttribute('data-message-id', String(finalized.id));
                state.addMessage(finalized.id);
                renderer.updateElement(optimistic.element, finalized, { statusOverride: finalized.status });
            }

            const modalInstance = window.bootstrap?.Modal ? window.bootstrap.Modal.getOrCreateInstance(templateModal) : null;
            modalInstance?.hide();
            setTemplateResult('Template message queued successfully.', 'success');
        } catch (error) {
            setTemplateResult(error instanceof Error ? error.message : 'Failed to send template message.', 'danger');
        } finally {
            if (sendButton) {
                sendButton.disabled = false;
                sendButton.textContent = 'Send Template';
            }
        }
    });

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

    document.querySelectorAll('[data-wa-open-template-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            void openTemplateModal('conversation');
        });
    });

    document.querySelectorAll('[data-wa-open-template-outreach]').forEach((button) => {
        button.addEventListener('click', () => {
            const hostModal = button.closest('.modal');
            if (hostModal && window.bootstrap?.Modal) {
                window.bootstrap.Modal.getOrCreateInstance(hostModal).hide();
            }

            void openTemplateModal('outreach');
        });
    });

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

    document.querySelectorAll('[data-wa-delete-contact]').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();

            const deleteUrl = button.getAttribute('data-wa-delete-url') || '';
            if (!deleteUrl) {
                return;
            }

            const shouldDelete = window.confirm('Delete this contact from the list?');
            if (!shouldDelete) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ _method: 'DELETE' }),
                });

                if (!response.ok) {
                    throw new Error('Delete failed');
                }

                window.location.reload();
            } catch (error) {
                button.disabled = false;
            }
        });
    });

    document.querySelectorAll('[data-wa-clear-conversation]').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();

            const clearUrl = button.getAttribute('data-wa-clear-url') || '';
            if (!clearUrl) {
                return;
            }

            const shouldClear = window.confirm('Clear all messages and notes in this conversation?');
            if (!shouldClear) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(clearUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                if (!response.ok) {
                    throw new Error('Clear failed');
                }

                window.location.reload();
            } catch (error) {
                button.disabled = false;
            }
        });
    });

    if (newChatForm) {
        const resultNode = newChatForm.querySelector('[data-wa-validation-result]');
        const validateButton = newChatForm.querySelector('[data-wa-validate-number]');
        const startButton = newChatForm.querySelector('[data-wa-start-chat]');

        const setValidationResult = (text, style = 'muted') => {
            if (!resultNode) {
                return;
            }

            resultNode.textContent = text;
            resultNode.className = `small text-${style}`;
        };

        const payloadFromForm = () => {
            const formData = new FormData(newChatForm);

            return {
                name: String(formData.get('name') || '').trim(),
                phone_number: String(formData.get('phone_number') || '').trim(),
                country_code: String(formData.get('country_code') || '').trim(),
            };
        };

        validateButton?.addEventListener('click', async () => {
            if (!quickChatValidateUrl) {
                return;
            }

            const payload = payloadFromForm();
            if (!payload.phone_number) {
                setValidationResult('Enter a phone number first.', 'warning');
                return;
            }

            try {
                setValidationResult('Validating number...', 'secondary');
                const data = await postJson(quickChatValidateUrl, payload, csrfToken);
                const status = String(data?.data?.validation_status || 'pending');

                if (status === 'valid') {
                    setValidationResult('Number is available on WhatsApp.', 'success');
                } else if (status === 'invalid') {
                    setValidationResult('Number is not available on WhatsApp.', 'danger');
                } else {
                    setValidationResult('Validation is pending. You can still start chat.', 'warning');
                }
            } catch (error) {
                setValidationResult('Validation failed. Please retry.', 'danger');
            }
        });

        newChatForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!quickChatStartUrl || !startButton) {
                return;
            }

            const payload = payloadFromForm();
            if (!payload.phone_number) {
                setValidationResult('Phone number is required.', 'warning');
                return;
            }

            startButton.disabled = true;
            const originalText = startButton.textContent;
            startButton.textContent = 'Starting...';

            try {
                const data = await postJson(quickChatStartUrl, payload, csrfToken);
                const conversationUrl = data?.data?.conversation_url;
                if (conversationUrl) {
                    window.location.assign(conversationUrl);
                    return;
                }

                window.location.reload();
            } catch (error) {
                setValidationResult(error.message || 'Failed to start chat.', 'danger');
            } finally {
                startButton.disabled = false;
                startButton.textContent = originalText || 'Start Chat';
            }
        });
    }

    document.querySelectorAll('[data-wa-open-save-contact]').forEach((button) => {
        button.addEventListener('click', () => {
            const phone = button.getAttribute('data-wa-phone') || '';
            const name = button.getAttribute('data-wa-name') || '';

            const savePhone = document.querySelector('[data-wa-save-phone]');
            const savePhoneDisplay = document.querySelector('[data-wa-save-phone-display]');
            const saveName = document.querySelector('[data-wa-save-name]');

            if (savePhone) {
                savePhone.value = phone;
            }

            if (savePhoneDisplay) {
                savePhoneDisplay.value = phone;
            }

            if (saveName) {
                saveName.value = name;
            }
        });
    });

    if (saveContactForm) {
        const resultNode = saveContactForm.querySelector('[data-wa-save-contact-result]');

        const setSaveResult = (text, style = 'muted') => {
            if (!resultNode) {
                return;
            }

            resultNode.textContent = text;
            resultNode.className = `small text-${style}`;
        };

        saveContactForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!saveContactUrl) {
                return;
            }

            const formData = new FormData(saveContactForm);
            const payload = {
                phone_number: String(formData.get('phone_number') || '').trim(),
                name: String(formData.get('name') || '').trim(),
            };

            try {
                setSaveResult('Saving contact...', 'secondary');
                await postJson(saveContactUrl, payload, csrfToken);
                setSaveResult('Contact saved successfully.', 'success');
                window.setTimeout(() => {
                    window.location.reload();
                }, 300);
            } catch (error) {
                setSaveResult(error.message || 'Failed to save contact.', 'danger');
            }
        });
    }
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
