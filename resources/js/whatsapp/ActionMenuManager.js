function sanitizeFilename(name) {
    return String(name || 'conversation')
        .replace(/[\\/:*?"<>|]+/g, '_')
        .replace(/\s+/g, ' ')
        .trim()
        .slice(0, 80) || 'conversation';
}

function buildTranscript(messageList, contactName, contactPhone) {
    if (!messageList) {
        return '';
    }

    const rows = Array.from(messageList.querySelectorAll('.wa-msg-row[data-message-id]'));

    const header = [
        `Conversation: ${contactName || 'Unknown Contact'}`,
        `Phone: ${contactPhone || 'N/A'}`,
        `Exported: ${new Date().toISOString()}`,
        '----------------------------------------',
        '',
    ];

    const lines = rows.map((row) => {
        const direction = row.classList.contains('incoming') ? 'Contact' : 'Admin';
        const body = row.getAttribute('data-message-body') || '[No body]';
        const timeNode = row.querySelector('.wa-meta span');
        const time = timeNode ? timeNode.textContent.trim() : '';

        return `[${time}] ${direction}: ${body}`;
    });

    return header.concat(lines).join('\n');
}

function downloadText(content, filename) {
    const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');

    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    link.remove();

    window.setTimeout(() => URL.revokeObjectURL(url), 200);
}

function toggleAccordionByKey(key) {
    if (!key) {
        return;
    }

    const target = document.querySelector(`[data-wa-accordion="${CSS.escape(key)}"]`);
    if (!target) {
        return;
    }

    target.open = !target.open;
    target.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
}

function openDetailsPanel(optionalAccordionKey) {
    const panel = document.querySelector('[data-wa-right-panel]');
    if (panel) {
        panel.classList.remove('d-none');
    }

    if (optionalAccordionKey) {
        const target = document.querySelector(`[data-wa-accordion="${CSS.escape(optionalAccordionKey)}"]`);
        if (target) {
            target.open = true;
            target.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    }
}

export function bootActionMenuManager(rootElement, activeConversationId) {
    const root = rootElement || document;

    if (document.__waActionMenuBooted) {
        return;
    }

    document.__waActionMenuBooted = true;

    const getMenuRoots = () => Array.from(document.querySelectorAll('[data-wa-actions-dropdown]'));

    const positionMenu = (menuRoot) => {
        if (!menuRoot) {
            return;
        }

        const trigger = menuRoot.querySelector('[data-wa-action-menu-trigger]');
        const menu = menuRoot.__detachedMenu || menuRoot.querySelector('[data-wa-action-menu]');
        if (!trigger || !menu) {
            return;
        }

        const triggerRect = trigger.getBoundingClientRect();
        const menuRect = menu.getBoundingClientRect();
        const viewportPadding = 10;

        let left = triggerRect.right - menuRect.width;
        let top = triggerRect.top; // Aligns the top of the menu exactly with the top of the icon



        if (left < viewportPadding) {
            left = viewportPadding;
        }

        const maxLeft = window.innerWidth - viewportPadding - menuRect.width;
        if (left > maxLeft) {
            left = Math.max(viewportPadding, maxLeft);
        }

        if (top + menuRect.height > window.innerHeight - viewportPadding) {
            top = Math.max(viewportPadding, triggerRect.top - menuRect.height - 6);
        }

        menu.style.setProperty('--wa-menu-left', `${Math.round(left)}px`);
        menu.style.setProperty('--wa-menu-top', `${Math.round(top)}px`);
    };

    const closeMenu = (menuRoot) => {
        if (!menuRoot) {
            return;
        }

        menuRoot.classList.remove('is-open');

        const trigger = menuRoot.querySelector('[data-wa-action-menu-trigger]');
        if (trigger) {
            trigger.setAttribute('aria-expanded', 'false');
        }

        // The menu might be detached to body, find it via reference if needed
        const menu = menuRoot.__detachedMenu || menuRoot.querySelector('[data-wa-action-menu]');
        if (menu) {
            menu.style.removeProperty('--wa-menu-left');
            menu.style.removeProperty('--wa-menu-top');
            menu.classList.remove('wa-menu-active');

            // Reattach to proper DOM location when closed
            if (menu.parentNode === document.body) {
                menuRoot.appendChild(menu);
            }
            menuRoot.__detachedMenu = null;
        }
    };

    const closeAllMenus = (exceptMenuRoot = null) => {
        getMenuRoots().forEach((menuRoot) => {
            if (exceptMenuRoot && menuRoot === exceptMenuRoot) {
                return;
            }

            closeMenu(menuRoot);
        });
    };

    const toggleMenu = (menuRoot) => {
        if (!menuRoot) {
            return;
        }

        const isOpen = menuRoot.classList.contains('is-open');
        closeAllMenus(menuRoot);

        if (isOpen) {
            closeMenu(menuRoot);
            return;
        }

        const menu = menuRoot.querySelector('[data-wa-action-menu]');
        if (menu) {
            menuRoot.__detachedMenu = menu;
            menu.__waDropdownRoot = menuRoot;
            document.body.appendChild(menu); // DOM Detach to solve all CSS layer/flicker issues
            menu.classList.add('wa-menu-active');
        }

        menuRoot.classList.add('is-open');

        // Ensure menu is fully visible this frame before position calculation
        if (menu) {
            menu.style.display = 'block';
        }

        positionMenu(menuRoot);

        const trigger = menuRoot.querySelector('[data-wa-action-menu-trigger]');
        if (trigger) {
            trigger.setAttribute('aria-expanded', 'true');
        }

        window.requestAnimationFrame(() => positionMenu(menuRoot));
    };

    window.addEventListener('resize', () => {
        getMenuRoots().forEach((menuRoot) => {
            if (menuRoot.classList.contains('is-open')) {
                positionMenu(menuRoot);
            }
        });
    });

    document.addEventListener('scroll', () => {
        getMenuRoots().forEach((menuRoot) => {
            if (menuRoot.classList.contains('is-open')) {
                positionMenu(menuRoot);
            }
        });
    }, true);

    const resolveDropdownRoot = (target) => {
        if (!target) return null;
        const inlineRoot = target.closest('[data-wa-actions-dropdown]');
        if (inlineRoot) return inlineRoot;
        const menuBlock = target.closest('[data-wa-action-menu]');
        return menuBlock ? menuBlock.__waDropdownRoot : null;
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-wa-action-menu-trigger]');
        if (trigger) {
            event.preventDefault();
            event.stopPropagation();
            toggleMenu(trigger.closest('[data-wa-actions-dropdown]'));
            return;
        }

        if (!resolveDropdownRoot(event.target)) {
            closeAllMenus();
        }
    });

    document.addEventListener('pointerdown', (event) => {
        if (resolveDropdownRoot(event.target)) {
            return;
        }

        closeAllMenus();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        closeAllMenus();
    });

    document.addEventListener('change', (event) => {
        const rootNode = resolveDropdownRoot(event.target);
        const select = event.target.closest('.form-select');
        if (!select || !rootNode) {
            return;
        }

        closeMenu(rootNode);
    });

    document.addEventListener('keydown', (event) => {
        const trigger = event.target.closest('[data-wa-action-menu-trigger]');
        if (!trigger) {
            return;
        }

        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            toggleMenu(trigger.closest('[data-wa-actions-dropdown]'));
        }
    });

    document.addEventListener('click', (event) => {
        const clickedMenuRoot = resolveDropdownRoot(event.target);
        if (!clickedMenuRoot) return;

        const submitButton = event.target.closest('[data-wa-menu-submit]');
        if (submitButton) {
            event.preventDefault();
            const formId = submitButton.getAttribute('data-wa-menu-submit');
            const form = formId ? document.getElementById(formId) : null;
            if (form) {
                form.requestSubmit();
            }
            closeMenu(clickedMenuRoot);
            return;
        }

        const detailsButton = event.target.closest('[data-wa-open-details-panel]');
        if (detailsButton) {
            const accordionKey = detailsButton.getAttribute('data-wa-open-accordion') || '';
            openDetailsPanel(accordionKey);
            closeMenu(clickedMenuRoot);
            return;
        }

        const accordionButton = event.target.closest('[data-wa-toggle-accordion]');
        if (accordionButton) {
            event.preventDefault();
            openDetailsPanel('');
            toggleAccordionByKey(accordionButton.getAttribute('data-wa-toggle-accordion') || '');
            closeMenu(clickedMenuRoot);
            return;
        }

        const shouldCloseOnAction = event.target.closest('[data-wa-menu-action], [data-wa-close-chat], [data-wa-open-save-contact], [data-wa-clear-conversation], [data-wa-delete-contact]');

        if (shouldCloseOnAction) {
            closeMenu(clickedMenuRoot);
        }

        const downloadButton = event.target.closest('[data-wa-download-thread]');
        if (!downloadButton) {
            return;
        }

        event.preventDefault();

        const targetConversationId = Number(downloadButton.getAttribute('data-wa-conversation-id') || 0) || null;
        const fallbackUrl = downloadButton.getAttribute('data-wa-download-fallback-url') || '';

        if (!activeConversationId || !targetConversationId || Number(activeConversationId) !== targetConversationId) {
            if (fallbackUrl) {
                window.location.assign(fallbackUrl);
            }
            return;
        }

        const messageList = document.querySelector('[data-wa-message-list]');
        const contactName = downloadButton.getAttribute('data-wa-conversation-name')
            || document.querySelector('[data-wa-contact-name]')?.textContent
            || 'Conversation';
        const contactPhone = downloadButton.getAttribute('data-wa-conversation-phone')
            || document.querySelector('[data-wa-contact-phone]')?.textContent
            || '';

        const transcript = buildTranscript(messageList, contactName, contactPhone);
        if (!transcript.trim()) {
            closeMenu(clickedMenuRoot);
            return;
        }

        const filename = `${sanitizeFilename(contactName)}-${new Date().toISOString().slice(0, 10)}.txt`;
        downloadText(transcript, filename);
        closeMenu(clickedMenuRoot);
    });
}
