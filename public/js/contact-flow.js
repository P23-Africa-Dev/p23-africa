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
    const errorKey = name => {
        const base = (name || '').replace('[]', '').split('.')[0];
        if (base === 'referral_other') return 'referral_source';
        if (base === 'country_code') return 'phone';
        return base;
    };
    const error = (name, message) => {
        const element = document.getElementById(`error-${errorKey(name)}`);
        if (element) element.textContent = message;
    };
    const clearErrors = () => {
        form.querySelectorAll('.cf-error').forEach(e => { e.textContent = ''; });
        form.querySelectorAll('[aria-invalid]').forEach(e => e.removeAttribute('aria-invalid'));
        if (status) status.hidden = true;
    };
    const markets = document.getElementById('cfMarkets');
    const marketInput = document.getElementById('cfMarket');
    const addMarket = () => {
        const value = marketInput.value.trim();
        if (!value) return;
        const existing = [...markets.querySelectorAll('input')].map(input => input.value.toLowerCase());
        if (existing.includes(value.toLowerCase())) {
            marketInput.value = '';
            marketInput.removeAttribute('aria-invalid');
            error('expansion_markets', '');
            return;
        }
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
        marketInput.removeAttribute('aria-invalid');
        error('expansion_markets', '');
    };
    document.getElementById('cfAddMarket').addEventListener('click', addMarket);
    marketInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addMarket(); } });
    markets.addEventListener('click', e => { const button = e.target.closest('button'); if (button) button.closest('li').remove(); });
    const country = document.getElementById('cfCountry');
    const phoneInput = document.getElementById('cfPhone');
    const updateCountry = () => {
        const selected = country.selectedOptions[0] || country.options[country.selectedIndex];
        if (!selected) return;
        document.getElementById('cfDialCode').textContent = country.value;
        const code = (selected.dataset.flag || 'gb').toLowerCase();
        const flagImg = document.getElementById('cfFlagImage');
        const emoji = document.getElementById('cfFlagEmoji');
        const base = flagImg?.dataset.base || '/images/contact';
        if (code === 'gb') {
            flagImg.src = `${base}/flag-gb.svg`;
            flagImg.hidden = false;
            emoji.hidden = true;
        } else if (code === 'za') {
            flagImg.src = `${base}/flag-za.png`;
            flagImg.hidden = false;
            emoji.hidden = true;
        } else {
            flagImg.hidden = true;
            emoji.hidden = false;
            emoji.textContent = [...code.toUpperCase()].map(c => String.fromCodePoint(127397 + c.charCodeAt(0))).join('');
        }
    };
    const normalizeAutofilledPhone = () => {
        if (!phoneInput) return;
        const raw = phoneInput.value.trim();
        if (!raw) return;
        if (raw.startsWith('+')) {
            const options = [...country.options].sort((a, b) => b.value.length - a.value.length);
            const match = options.find(opt => raw.startsWith(opt.value));
            if (match) {
                country.value = match.value;
                updateCountry();
                const stripped = raw.slice(match.value.length).replace(/^[\s\-()]+/, '');
                if (stripped) phoneInput.value = stripped;
            }
        }
    };
    country.addEventListener('change', updateCountry);
    country.addEventListener('input', updateCountry);
    if (phoneInput) {
        phoneInput.addEventListener('change', normalizeAutofilledPhone);
        phoneInput.addEventListener('input', normalizeAutofilledPhone);
    }
    updateCountry();
    const referralOther = document.getElementById('cfReferralOther');
    const updateReferral = () => {
        if (!referralOther) return;
        const isOther = form.querySelector('[name="referral_source"]:checked')?.value === 'Other';
        referralOther.required = isOther;
        if (!isOther) {
            referralOther.removeAttribute('aria-invalid');
        }
        error('referral_source', '');
    };
    form.querySelectorAll('[name="referral_source"]').forEach(input => input.addEventListener('change', updateReferral));
    if (referralOther) {
        referralOther.addEventListener('input', () => {
            if (referralOther.value.trim()) {
                const otherRadio = form.querySelector('[name="referral_source"][value="Other"]');
                if (otherRadio && !otherRadio.checked) {
                    otherRadio.checked = true;
                    updateReferral();
                }
            }
        });
    }
    updateReferral();
    const getValidationMessage = input => {
        if (input.name === 'name' && input.validity.valueMissing) return 'Please enter your full name.';
        if (input.name === 'phone') {
            if (input.validity.valueMissing) return 'Please enter your phone number.';
            if (input.validity.patternMismatch) return 'Please enter a valid phone number (5–20 digits).';
        }
        if (input.name === 'company_name' && input.validity.valueMissing) return 'Please enter your company or brand name.';
        if (input.name === 'role' && input.validity.valueMissing) return 'Please enter your role in the company.';
        if (input.name === 'email') {
            if (input.validity.valueMissing) return 'Please enter your email address.';
            if (input.validity.typeMismatch) return 'Please enter a valid email address.';
        }
        if (input.name === 'industry') return 'Please select your industry.';
        if (input.name === 'company_website' && input.validity.valueMissing) return 'Please enter your company website or LinkedIn page.';
        if (input.name === 'business_description' && input.validity.valueMissing) return 'Please briefly describe what your business does.';
        if (input.name === 'message' && input.validity.valueMissing) return 'Please briefly describe what you want to accomplish.';
        if (input.name === 'referral_source') return 'Please select how you heard about us.';
        if (input.name === 'referral_other') return 'Please specify how you heard about us.';
        if (input.name === 'start_timeline') return 'Please select when you are looking to get started.';
        if (input.name === 'budget_range' && (input.validity.badInput || input.validity.rangeUnderflow || input.validity.valueMissing)) {
            return 'Please enter a valid budget amount.';
        }
        return input.validationMessage;
    };
    const validateStep = index => {
        const step = steps[index];
        let firstInvalid = null;
        const markInvalid = (element, fieldName, message) => {
            if (element) element.setAttribute('aria-invalid', 'true');
            error(fieldName, message);
            if (!firstInvalid && element) firstInvalid = element;
        };
        if (index === 1) {
            addMarket();
            const serviceCheckbox = step.querySelector('[name="selected_services[]"], [name="selected_goals[]"]');
            const serviceGroupName = serviceCheckbox?.name.replace('[]', '') || 'selected_services';
            if (serviceCheckbox && !step.querySelector(`[name="${serviceCheckbox.name}"]:checked`)) {
                error(serviceGroupName, 'Please select at least one service.');
                if (!firstInvalid) firstInvalid = serviceCheckbox;
            }
            if (markets && !markets.querySelector('input')) {
                markInvalid(marketInput, 'expansion_markets', 'Please add at least one expansion market.');
            }
        }
        const seenRadioGroups = new Set();
        for (const input of step.querySelectorAll('input, select, textarea')) {
            if (input.type === 'hidden' || input.disabled || input === marketInput) continue;
            if (input.type === 'radio') {
                if (seenRadioGroups.has(input.name)) continue;
                seenRadioGroups.add(input.name);
            }
            if (!input.checkValidity()) {
                markInvalid(input, input.name, getValidationMessage(input));
            }
        }
        if (firstInvalid) {
            firstInvalid.focus();
            if (typeof firstInvalid.reportValidity === 'function' && firstInvalid !== marketInput && firstInvalid.type !== 'checkbox') {
                firstInvalid.reportValidity();
            }
            return false;
        }
        return true;
    };
    const resolveBudgetOutcome = budgetValue => {
        const budget = Number(budgetValue || 0);
        if (budget < 1500) {
            return { route: 'resources', url: form.dataset.resourceUrl || '/resource-hub' };
        }
        if (budget <= 2500) {
            return { route: 'consultation', url: form.dataset.consultationUrl || '/contact/consultation/schedule' };
        }
        return { route: 'discovery', url: form.dataset.discoveryUrl || '/contact/discovery-call' };
    };
    const buildAssessmentPayload = route => {
        const services = [...form.querySelectorAll('[name="selected_services[]"]:checked, [name="selected_goals[]"]:checked')].map(el => el.value);
        const expansionMarkets = [...form.querySelectorAll('[name="expansion_markets[]"]')].map(el => el.value);
        return {
            outcome: route,
            name: document.getElementById('cfName')?.value || '',
            email: document.getElementById('cfEmail')?.value || '',
            phone: document.getElementById('cfPhone')?.value || '',
            country_code: document.getElementById('cfCountry')?.value || '+44',
            company_name: document.getElementById('cfCompany')?.value || '',
            company_website: document.getElementById('cfWebsite')?.value || '',
            role: document.getElementById('cfRole')?.value || '',
            industry: document.getElementById('cfIndustry')?.value || 'Other',
            business_description: (document.getElementById('cfBusiness')?.value || document.getElementById('cfGoals')?.value || '').slice(0, 400),
            message: (document.getElementById('cfGoals')?.value || '').slice(0, 400),
            services,
            expansion_markets: expansionMarkets,
            referral_source: form.querySelector('[name="referral_source"]:checked')?.value || '',
            start_timeline: form.querySelector('[name="start_timeline"]:checked')?.value || '',
            budget_range: Number(document.getElementById('cfBudget')?.value || 0),
            additional_notes: (document.getElementById('cfNotes')?.value || '').slice(0, 300),
        };
    };
    const encodeAssessment = payload => {
        try {
            const bytes = new TextEncoder().encode(JSON.stringify(payload));
            let binary = '';
            bytes.forEach(b => { binary += String.fromCharCode(b); });
            return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
        } catch {
            return '';
        }
    };
    const withAssessmentParam = (url, route) => {
        const token = encodeAssessment(buildAssessmentPayload(route));
        if (!token) return url;
        const separator = url.includes('?') ? '&' : '?';
        return `${url}${separator}assessment=${encodeURIComponent(token)}`;
    };
    back.addEventListener('click', () => { if (!submitting && current > 0) { clearErrors(); showStep(current - 1); } });
    const handleFieldChange = e => {
        if (e.target === marketInput) {
            marketInput.removeAttribute('aria-invalid');
            error('expansion_markets', '');
            return;
        }
        e.target.removeAttribute('aria-invalid');
        if (e.target.name) {
            error(e.target.name, '');
        }
    };
    form.addEventListener('input', handleFieldChange);
    form.addEventListener('change', handleFieldChange);
    form.addEventListener('submit', async e => {
        e.preventDefault();
        if (submitting) return;
        clearErrors();
        if (!validateStep(current)) return;
        if (current < steps.length - 1) {
            showStep(current + 1);
            return;
        }
        for (let i = 0; i < steps.length; i++) {
            if (!validateStep(i)) {
                showStep(i);
                return;
            }
        }
        const budgetInput = document.getElementById('cfBudget');
        const fallbackOutcome = resolveBudgetOutcome(budgetInput?.value);
        const submitLabel = document.getElementById('cfSubmitLabel');
        submitting = true;
        next.disabled = true;
        back.disabled = true;
        form.setAttribute('aria-busy', 'true');
        if (submitLabel) submitLabel.textContent = 'Sending…';
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await response.json().catch(() => ({}));
            if (response.status === 422 && result.errors) {
                let firstStep = steps.length - 1;
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
                if (status) {
                    status.textContent = 'Please check the highlighted fields and try again.';
                    status.hidden = false;
                }
                return;
            }
            const outcome = result.outcome && result.outcome.route ? result.outcome : fallbackOutcome;
            if (outcome.route === 'consultation' || outcome.route === 'discovery') {
                window.location.assign(withAssessmentParam(outcome.url, outcome.route));
                return;
            }
            form.hidden = true;
            const success = document.getElementById('cfSuccess');
            if (success) {
                const successLink = document.getElementById('cfSuccessLink') || success.querySelector('a');
                if (successLink && outcome.url) {
                    successLink.href = outcome.url;
                }
                success.hidden = false;
                success.focus();
                success.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth', block: 'start' });
            }
        } catch {
            if (fallbackOutcome.route === 'consultation' || fallbackOutcome.route === 'discovery') {
                window.location.assign(withAssessmentParam(fallbackOutcome.url, fallbackOutcome.route));
                return;
            }
            // If offline or server unreachable, still allow the Route 3 success state to display.
            if (fallbackOutcome.route === 'resources') {
                form.hidden = true;
                const success = document.getElementById('cfSuccess');
                if (success) {
                    const successLink = document.getElementById('cfSuccessLink') || success.querySelector('a');
                    if (successLink && fallbackOutcome.url) {
                        successLink.href = fallbackOutcome.url;
                    }
                    success.hidden = false;
                    success.focus();
                }
                return;
            }
            if (status) {
                status.textContent = 'We could not connect. Your answers are still here; please check your connection and try again.';
                status.hidden = false;
            }
        } finally {
            submitting = false;
            next.disabled = false;
            back.disabled = current === 0;
            form.removeAttribute('aria-busy');
            if (submitLabel) submitLabel.textContent = 'Send enquiry';
        }
    });
    showStep(0, false);
    const firstError = [...form.querySelectorAll('.cf-error')].find(element => element.textContent.trim());
    if (firstError) {
        const index = steps.indexOf(firstError.closest('.cf-step'));
        if (index >= 0) showStep(index, false);
    }
})();

