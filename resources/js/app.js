import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

async function bootPageModules() {
    const hasWhatsAppChat = Boolean(document.querySelector('[data-wa-root]'));
    const hasTemplateBuilder = Boolean(document.querySelector('[data-wa-template-builder]'));

    if (hasWhatsAppChat) {
        const module = await import('./whatsapp/conversations.js');
        if (typeof module.bootWhatsAppConversations === 'function') {
            module.bootWhatsAppConversations();
        }
    }

    if (hasTemplateBuilder) {
        const module = await import('./whatsapp/TemplatePreviewManager.js');
        if (typeof module.bootTemplatePreviewManager === 'function') {
            module.bootTemplatePreviewManager();
        }
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        void bootPageModules();
    }, { once: true });
} else {
    void bootPageModules();
}
