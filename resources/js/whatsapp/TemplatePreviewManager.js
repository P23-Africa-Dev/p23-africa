function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function extractJsonPreview(jsonText) {
    try {
        const parsed = JSON.parse(jsonText);
        const components = Array.isArray(parsed.components) ? parsed.components : [];
        const body = components.find((item) => String(item?.type || '').toUpperCase() === 'BODY');
        return String(body?.text || '');
    } catch {
        return '';
    }
}

function replaceVariables(content, variables) {
    if (!content) {
        return '';
    }

    return String(content).replace(/\{\{\s*([a-zA-Z0-9_.]+)\s*\}\}/g, (full, key) => {
        if (Object.prototype.hasOwnProperty.call(variables, key)) {
            return String(variables[key]);
        }

        return full;
    });
}

export function bootTemplatePreviewManager() {
    const root = document.querySelector('[data-wa-template-builder]');

    if (!root) {
        return;
    }

    const formatInput = root.querySelector('[data-template-format-input]');
    const formatButtons = Array.from(root.querySelectorAll('[data-format-switch]'));
    const formatPanes = Array.from(root.querySelectorAll('[data-format-pane]'));
    const previewBubble = root.querySelector('[data-preview-bubble]');
    const previewFormatLabel = root.querySelector('[data-preview-format-label]');
    const jsonEditor = root.querySelector('[data-json-editor]');
    const jsonError = root.querySelector('[data-json-error]');
    const richEditor = root.querySelector('[data-rich-editor]');
    const richHidden = root.querySelector('[data-rich-hidden]');
    const htmlEditor = root.querySelector('[data-html-editor]');
    const htmlPreviewSurface = root.querySelector('[data-html-preview-surface]');
    const htmlPreviewToggle = root.querySelector('[data-html-preview-toggle]');
    const previewVariablesField = root.querySelector('[data-template-preview-vars]');
    const variableButtons = Array.from(root.querySelectorAll('[data-insert-variable]'));
    const metadataInput = root.querySelector('[data-editor-metadata]');

    const variables = {
        name: 'Ada',
        company: 'P23 Africa',
        date: new Date().toLocaleDateString(),
        event: 'Summit 2026',
        event_name: 'Nigerian Business Market',
        event_date: new Date().toLocaleDateString(),
        location: 'Lagos',
        seat_code: 'P23-1004',
        email: 'attendee@example.com',
        phone: '+2348012345678',
        booking_date: new Date().toLocaleDateString(),
    };

    let activeFormat = formatInput?.value || 'json';

    function updateMetadata() {
        if (!metadataInput) {
            return;
        }

        const metadata = {
            preview_variables: variables,
            updated_at: new Date().toISOString(),
        };

        metadataInput.value = JSON.stringify(metadata);
    }

    function ensureHiddenRichContent() {
        if (richEditor && richHidden) {
            richHidden.value = richEditor.innerHTML;
        }
    }

    function refreshPreview() {
        if (!previewBubble) {
            return;
        }

        activeFormat = formatInput?.value || activeFormat;

        ensureHiddenRichContent();

        let previewHtml = '';

        if (activeFormat === 'json') {
            const jsonPreview = extractJsonPreview(jsonEditor?.value || '');
            const replaced = replaceVariables(jsonPreview, variables);
            previewHtml = escapeHtml(replaced).replace(/\n/g, '<br>');
        } else if (activeFormat === 'rich_text') {
            const richContent = richEditor?.innerHTML || '';
            previewHtml = replaceVariables(richContent, variables);
        } else {
            const htmlContent = htmlEditor?.value || '';
            previewHtml = replaceVariables(htmlContent, variables);
            if (htmlPreviewSurface && !htmlPreviewSurface.classList.contains('d-none')) {
                htmlPreviewSurface.innerHTML = previewHtml;
            }
        }

        previewBubble.innerHTML = previewHtml || '<span class="text-muted">Start writing your template to preview it here.</span>';

        if (previewFormatLabel) {
            previewFormatLabel.textContent = activeFormat.toUpperCase();
        }

        updateMetadata();
    }

    function switchFormat(format) {
        activeFormat = format;

        if (formatInput) {
            formatInput.value = format;
        }

        formatButtons.forEach((button) => {
            const selected = button.getAttribute('data-format-switch') === format;
            button.classList.toggle('btn-success', selected);
            button.classList.toggle('btn-outline-secondary', !selected);
        });

        formatPanes.forEach((pane) => {
            pane.classList.toggle('d-none', pane.getAttribute('data-format-pane') !== format);
        });

        refreshPreview();
    }

    if (formatInput) {
        formatInput.addEventListener('change', () => {
            switchFormat(formatInput.value || 'json');
        });
    }

    root.addEventListener('wa-template-format-changed', () => {
        switchFormat(formatInput?.value || 'json');
    });

    function insertAtCursor(value) {
        const variableToken = `{{${value}}}`;

        if (activeFormat === 'rich_text' && richEditor) {
            richEditor.focus();
            document.execCommand('insertText', false, variableToken);
            ensureHiddenRichContent();
            refreshPreview();
            return;
        }

        const target = activeFormat === 'html' ? htmlEditor : jsonEditor;

        if (!target) {
            return;
        }

        const start = target.selectionStart ?? target.value.length;
        const end = target.selectionEnd ?? target.value.length;
        const nextValue = `${target.value.slice(0, start)}${variableToken}${target.value.slice(end)}`;

        target.value = nextValue;
        const nextCursor = start + variableToken.length;
        target.selectionStart = nextCursor;
        target.selectionEnd = nextCursor;
        target.focus();

        refreshPreview();
    }

    if (root.querySelector('[data-json-pretty]')) {
        root.querySelector('[data-json-pretty]').addEventListener('click', () => {
            try {
                const parsed = JSON.parse(jsonEditor?.value || '{}');
                if (jsonEditor) {
                    jsonEditor.value = JSON.stringify(parsed, null, 2);
                }
                if (jsonError) {
                    jsonError.classList.add('d-none');
                }
            } catch (error) {
                if (jsonError) {
                    jsonError.textContent = `Invalid JSON: ${error.message}`;
                    jsonError.classList.remove('d-none');
                }
            }
            refreshPreview();
        });
    }

    if (root.querySelector('[data-json-lint]')) {
        root.querySelector('[data-json-lint]').addEventListener('click', () => {
            try {
                JSON.parse(jsonEditor?.value || '{}');
                if (jsonError) {
                    jsonError.textContent = 'JSON is valid.';
                    jsonError.classList.remove('d-none');
                    jsonError.classList.remove('text-danger');
                    jsonError.classList.add('text-success');
                }
            } catch (error) {
                if (jsonError) {
                    jsonError.textContent = `Invalid JSON: ${error.message}`;
                    jsonError.classList.remove('d-none');
                    jsonError.classList.remove('text-success');
                    jsonError.classList.add('text-danger');
                }
            }
        });
    }

    if (richEditor) {
        richEditor.addEventListener('input', () => {
            ensureHiddenRichContent();
            refreshPreview();
        });
    }

    Array.from(root.querySelectorAll('[data-rich-command]')).forEach((button) => {
        button.addEventListener('click', () => {
            const command = button.getAttribute('data-rich-command');
            const value = button.getAttribute('data-rich-value');
            richEditor?.focus();
            document.execCommand(command, false, value || null);
            ensureHiddenRichContent();
            refreshPreview();
        });
    });

    const linkButton = root.querySelector('[data-rich-link]');
    if (linkButton) {
        linkButton.addEventListener('click', () => {
            const link = window.prompt('Enter URL');
            if (!link) {
                return;
            }

            richEditor?.focus();
            document.execCommand('createLink', false, link);
            ensureHiddenRichContent();
            refreshPreview();
        });
    }

    const mentionButton = root.querySelector('[data-rich-mention]');
    if (mentionButton) {
        mentionButton.addEventListener('click', () => {
            richEditor?.focus();
            document.execCommand('insertText', false, '@team ');
            ensureHiddenRichContent();
            refreshPreview();
        });
    }

    const headingButton = root.querySelector('[data-rich-heading]');
    if (headingButton) {
        headingButton.addEventListener('click', () => {
            richEditor?.focus();
            document.execCommand('formatBlock', false, 'h3');
            ensureHiddenRichContent();
            refreshPreview();
        });
    }

    const emojiButton = root.querySelector('[data-rich-emoji]');
    if (emojiButton) {
        emojiButton.addEventListener('click', () => {
            richEditor?.focus();
            document.execCommand('insertText', false, '😊');
            ensureHiddenRichContent();
            refreshPreview();
        });
    }

    if (htmlEditor) {
        htmlEditor.addEventListener('input', refreshPreview);
    }

    if (htmlPreviewToggle && htmlPreviewSurface) {
        htmlPreviewToggle.addEventListener('click', () => {
            htmlPreviewSurface.classList.toggle('d-none');
            refreshPreview();
        });
    }

    if (jsonEditor) {
        jsonEditor.addEventListener('input', () => {
            if (jsonError) {
                jsonError.classList.add('d-none');
            }
            refreshPreview();
        });
    }

    variableButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const variable = button.getAttribute('data-insert-variable');
            if (variable) {
                insertAtCursor(variable);
            }
        });
    });

    if (previewVariablesField) {
        previewVariablesField.addEventListener('change', () => {
            const raw = previewVariablesField.value || '';
            if (!raw.trim()) {
                refreshPreview();
                return;
            }

            try {
                const parsed = JSON.parse(raw);
                if (parsed && typeof parsed === 'object') {
                    Object.keys(parsed).forEach((key) => {
                        variables[key] = parsed[key];
                    });
                }
            } catch {
                // Preview vars are optional.
            }

            refreshPreview();
        });
    }

    root.querySelector('form')?.addEventListener('submit', (event) => {
        ensureHiddenRichContent();

        if (activeFormat === 'json') {
            try {
                JSON.parse(jsonEditor?.value || '{}');
            } catch (error) {
                event.preventDefault();
                if (jsonError) {
                    jsonError.textContent = `Invalid JSON: ${error.message}`;
                    jsonError.classList.remove('d-none');
                }
            }
        }
    });

    switchFormat(activeFormat);
    refreshPreview();
}
