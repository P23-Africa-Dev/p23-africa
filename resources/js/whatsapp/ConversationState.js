export default class ConversationState {
    constructor(options = {}) {
        this.conversationId = options.conversationId ? Number(options.conversationId) : null;
        this.pollUrl = options.pollUrl || '';
        this.statusPollUrl = options.statusPollUrl || '';
        this.sidebarPollUrl = options.sidebarPollUrl || this.pollUrl;
        this.csrfToken = options.csrfToken || '';
        this.pollIntervalMs = Number(options.pollIntervalMs || 5000);
        this.messageCursor = options.messageCursor || options.cursor || new Date().toISOString();
        this.statusCursor = options.statusCursor || options.cursor || new Date().toISOString();
        this.sidebarCursor = options.sidebarCursor || options.cursor || new Date().toISOString();
        this.unreadTotal = Number(options.unreadTotal || 0);
        this.knownMessageIds = new Set((options.knownMessageIds || []).map((id) => String(id)));
        this.isSending = false;
        this.isChatClosed = false;
    }

    hasMessage(messageId) {
        return this.knownMessageIds.has(String(messageId));
    }

    addMessage(messageId) {
        this.knownMessageIds.add(String(messageId));
    }

    updateCursor(cursor) {
        this.updateMessageCursor(cursor);
    }

    updateMessageCursor(cursor) {
        if (!cursor) {
            return;
        }

        this.messageCursor = cursor;
    }

    updateStatusCursor(cursor) {
        if (!cursor) {
            return;
        }

        this.statusCursor = cursor;
    }

    updateSidebarCursor(cursor) {
        if (!cursor) {
            return;
        }

        this.sidebarCursor = cursor;
    }

    updateUnreadTotal(count) {
        this.unreadTotal = Number(count || 0);
    }
}