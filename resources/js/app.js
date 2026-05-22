import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

async function bootPageModules() {
    const hasWhatsAppChat = Boolean(document.querySelector('[data-wa-root]'));

    if (hasWhatsAppChat) {
        const module = await import('./whatsapp/conversations.js');
        if (typeof module.bootWhatsAppConversations === 'function') {
            module.bootWhatsAppConversations();
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
