(() => {
    const form = document.getElementById('contactFlow');
    if (!form) return;
    const steps = [...form.querySelectorAll('.cf-step')];
    const back = document.getElementById('cfBack');
    const next = document.getElementById('cfNext');
    const status = document.getElementById('cfStatus');
    const titles = ['Business Overview', 'What Do You Need', 'Readiness and Budget'];
    const descriptions = [
        'First, let’s get to know you and your business so we can better understand how to support you.',
        'Tell us where your business wants to go and what challenges you need help solving',
        'This is the commercial qualification stage.'
    ];
    let current = 0;
    let submitting = false;
    const showStep = (index, focus = true) => {
        current = index;
        steps.forEach((step, i) => { step.hidden = i !== index; });
        form.dataset.currentStep = String(index + 1);
        document.getElementById('cfTitle').textContent = titles[index];
        document.getElementById('cfDescription').textContent = descriptions[index];
        const number = String(index + 1).padStart(2, '0');
        document.getElementById('cfStepNumber').textContent = number;
        document.getElementById('cfStepCount').textContent = `${number} of 03`;
        const ring = document.getElementById('cfRing');
        ring.src = `${ring.dataset.ringBase}/ring-${index + 1}.svg`;
        document.getElementById('cfProgress').setAttribute('aria-valuenow', String(index + 1));
        document.querySelector('#cfProgress > span').style.width = ['37.6%', '63.7%', '100%'][index];
        back.disabled = index === 0;
        next.setAttribute('aria-label', index === 2 ? 'Send enquiry' : 'Next step');
        document.getElementById('cfSubmitLabel').hidden = index !== 2;
        if (focus) {
            document.getElementById('cfTitle').focus({ preventScroll: true });
            form.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth', block: 'start' });
        }
    };
    const showStatus = message => {
        status.textContent = message;
        status.hidden = false;
    };
    const error = (name, message) => {
        const element = document.getElementById(`error-${name.split('.')[0]}`);
        if (element) element.textContent = message;
    };
    const clearErrors = () => {
        form.querySelectorAll('.cf-error').forEach(e => { e.textContent = ''; });
        form.querySelectorAll('[aria-invalid]').forEach(e => e.removeAttribute('aria-invalid'));
        status.hidden = true;
    };
    const markets = document.getElementById('cfMarkets');
    const marketInput = document.getElementById('cfMarket');
    const addMarket = () => {
        const value = marketInput.value.trim();
        if (!value) return;
        const existing = [...markets.querySelectorAll('input')].map(input => input.value.toLowerCase());
        if (existing.includes(value.toLowerCase())) { marketInput.value = ''; return; }
        if (existing.length >= 10) { error('expansion_markets', 'Please choose up to 10 markets.'); return; }
        const item = document.createElement('li');
        const text = document.createElement('span');
        text.textContent = value;
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'expansion_markets[]'; input.value = value;
        const remove = document.createElement('button');
        remove.type = 'button'; remove.textContent = '×'; remove.setAttribute('aria-label', `Remove ${value}`);
        item.append(text, input, remove); markets.append(item);
        marketInput.value = '';
        error('expansion_markets', '');
    };
    document.getElementById('cfAddMarket').addEventListener('click', addMarket);
    marketInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addMarket(); } });
    markets.addEventListener('click', e => { const button = e.target.closest('button'); if (button) button.closest('li').remove(); });
    const country = document.getElementById('cfCountry');
    const updateCountry = () => {
        document.getElementById('cfDialCode').textContent = country.value;
        const code = country.selectedOptions[0].dataset.flag;
        const emoji = document.getElementById('cfFlagEmoji');
        document.getElementById('cfFlagImage').hidden = code !== 'za';
        emoji.hidden = code === 'za';
        emoji.textContent = [...code.toUpperCase()].map(c => String.fromCodePoint(127397 + c.charCodeAt(0))).join('');
    };
    country.addEventListener('change', updateCountry);
    updateCountry();
    const referralOther = document.getElementById('cfReferralOther');
    const updateReferral = () => { referralOther.required = form.querySelector('[name="referral_source"]:checked')?.value === 'Other'; };
    form.querySelectorAll('[name="referral_source"]').forEach(input => input.addEventListener('change', updateReferral));
    updateReferral();
    const validateStep = index => {
        const step = steps[index];
        if (index === 1) {
            addMarket();
            if (!form.querySelector('[name="selected_services[]"]:checked')) {
                error('selected_services', 'Please select at least one service.');
                form.querySelector('[name="selected_services[]"]').focus();
                return false;
            }
            if (!markets.querySelector('input')) {
                error('expansion_markets', 'Please add at least one expansion market.');
                marketInput.focus(); return false;
            }
        }
        for (const input of step.querySelectorAll('input, select, textarea')) {
            if (!input.checkValidity()) {
                input.setAttribute('aria-invalid', 'true');
                error(input.name.replace('[]', ''), input.validationMessage);
                input.reportValidity(); return false;
            }
        }
        return true;
    };
    back.addEventListener('click', () => { if (!submitting && current > 0) { clearErrors(); showStep(current - 1); } });
    form.addEventListener('input', e => {
        e.target.removeAttribute('aria-invalid');
        error(e.target.name?.replace('[]', '') || '', '');
    });
    form.addEventListener('submit', async e => {
        e.preventDefault();
        if (submitting) return;
        clearErrors();
        if (!validateStep(current)) return;
        if (current < 2) { showStep(current + 1); return; }
        // Recheck earlier steps before sending, including values restored after a server error.
        for (let i = 0; i < steps.length; i++) {
            showStep(i, false);
            if (!validateStep(i)) { showStep(i); return; }
        }
        showStep(2, false);
        submitting = true; next.disabled = true; back.disabled = true;
        form.setAttribute('aria-busy', 'true');
        document.getElementById('cfSubmitLabel').textContent = 'Sending…';
        try {
            const response = await fetch(form.action, {
                method: 'POST', body: new FormData(form),
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json().catch(() => ({}));
            if (!response.ok || result.success !== true) {
                if (response.status === 422 && result.errors) {
                    let firstStep = 2;
                    Object.entries(result.errors).forEach(([name, messages]) => {
                        const fieldName = name.split('.')[0];
                        error(fieldName, messages[0]);
                        const control = form.elements.namedItem(fieldName) || form.elements.namedItem(`${fieldName}[]`);
                        const element = control instanceof RadioNodeList ? control[0] : control;
                        if (element?.closest('.cf-step')) {
                            element.setAttribute('aria-invalid', 'true');
                            firstStep = Math.min(firstStep, steps.indexOf(element.closest('.cf-step')));
                        }
                    });
                    showStep(firstStep);
                    showStatus('Please check the highlighted fields and try again.');
                } else {
                    showStatus(response.status === 419 ? 'Your session has expired. Please refresh the page and try again.'
                        : response.status === 429 ? 'Too many attempts. Please wait a minute and try again.'
                        : result.message || 'Your enquiry could not be sent. Please try again.');
                }
                return;
            }
            form.hidden = true;
            const success = document.getElementById('cfSuccess');
            success.hidden = false;
            success.focus();
        } catch {
            showStatus('We could not connect. Your answers are still here; please check your connection and try again.');
        } finally {
            submitting = false; next.disabled = false; back.disabled = current === 0;
            form.removeAttribute('aria-busy');
            document.getElementById('cfSubmitLabel').textContent = 'Send enquiry';
        }
    });
    showStep(0, false);
    const firstError = [...form.querySelectorAll('.cf-error')].find(element => element.textContent.trim());
    if (firstError) {
        const index = steps.indexOf(firstError.closest('.cf-step'));
        if (index >= 0) showStep(index, false);
    }
})();
