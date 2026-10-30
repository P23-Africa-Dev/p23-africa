<script>
    // FORM MANAGEMENT AND VALIDATION
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('brnForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        const responseMessage = document.getElementById('responseMessage');
        const modal = new bootstrap.Modal(document.getElementById('responseModal'));

        // Form persistence constants
        const FORM_STORAGE_KEY = 'brn_form_data';
        const STEP_STORAGE_KEY = 'brn_current_step';

        // Save form data to localStorage
        function saveFormData() {
            const formData = {};
            const formElements = form.querySelectorAll('input, select, textarea');

            formElements.forEach(element => {
                if (element.type === 'radio' || element.type === 'checkbox') {
                    if (element.checked) {
                        formData[element.name] = element.value;
                    }
                } else if (element.name && element.value) {
                    formData[element.name] = element.value;
                }
            });

            // Save current step using currentStep variable
            const stepNames = ['form1', 'form2', 'form3', 'form4'];
            if (currentStep >= 0 && currentStep < stepNames.length) {
                const stepName = stepNames[currentStep];
                localStorage.setItem(STEP_STORAGE_KEY, stepName);
            }

            localStorage.setItem(FORM_STORAGE_KEY, JSON.stringify(formData));
        }

        // Restore form data from localStorage
        function restoreFormData() {
            try {
                const savedData = localStorage.getItem(FORM_STORAGE_KEY);
                const savedStep = localStorage.getItem(STEP_STORAGE_KEY);

                if (savedData) {
                    const formData = JSON.parse(savedData);

                    // Restore input values
                    Object.keys(formData).forEach(fieldName => {
                        const elements = form.querySelectorAll(`[name="${fieldName}"]`);

                        elements.forEach(element => {
                            if (element.type === 'radio' || element.type === 'checkbox') {
                                if (element.value === formData[fieldName]) {
                                    element.checked = true;
                                }
                            } else {
                                element.value = formData[fieldName];
                            }
                        });
                    });

                    // Restore custom select values
                    if (formData.position) {
                        updateCustomSelect('title', formData.position);
                    }
                    if (formData.industry) {
                        updateCustomSelect('industry', formData.industry);
                    }
                    if (formData.number_of_employees) {
                        updateCustomSelect('employees', formData.number_of_employees);
                    }
                    if (formData.years_of_operation) {
                        updateCustomSelect('revenue', formData.years_of_operation);
                    }
                    if (formData.selected_outcome) {
                        updateCustomSelect('interest', formData.selected_outcome);
                    }

                    // Auto-revalidate email if it exists and is valid format
                    if (formData.email && isValidEmail(formData.email)) {
                        setTimeout(() => {
                            validateEmailInDatabase(formData.email);
                        }, 100);
                    }

                    // Note: Step restoration is now handled during initialization
                }
            } catch (error) {
                // console.error('Error restoring form data:', error);
            }
        }

        // Clear form data from localStorage
        function clearFormData() {
            localStorage.removeItem(FORM_STORAGE_KEY);
            localStorage.removeItem(STEP_STORAGE_KEY);
        }

        // Helper function to update custom select display
        function updateCustomSelect(selectName, value) {
            const customSelect = document.querySelector(`[data-select="${selectName}"]`);
            if (customSelect) {
                const trigger = customSelect.querySelector('.custom-select-text');
                const option = customSelect.querySelector(`[data-value="${value}"]`);
                const hiddenSelect = document.querySelector(`select[name="${getFieldName(selectName)}"]`);

                if (option && trigger) {
                    trigger.textContent = option.textContent;
                    customSelect.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove(
                        'active'));
                    option.classList.add('active');
                }

                if (hiddenSelect) {
                    hiddenSelect.value = value;
                }
            }
        }

        // Map custom select names to form field names
        function getFieldName(selectName) {
            const mapping = {
                'title': 'position',
                'industry': 'industry',
                'employees': 'number_of_employees',
                'revenue': 'years_of_operation',
                'interest': 'selected_outcome',
                'country': 'country'
            };
            return mapping[selectName] || selectName;
        }

        // Email validation utility functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showEmailStatus(type) {
            const emailStatus = document.getElementById('email-status');
            const emailCheckmark = document.getElementById('email-checkmark');
            const emailSpinner = document.getElementById('email-spinner');

            emailStatus.style.display = 'block';
            emailCheckmark.style.display = type === 'success' ? 'block' : 'none';
            emailSpinner.style.display = type === 'loading' ? 'block' : 'none';

            if (type === 'hide') {
                emailStatus.style.display = 'none';
            }
        }

        function validateEmailInDatabase(email) {
            const emailInput = document.querySelector('input[name="email"]');

            if (!email || !isValidEmail(email)) {
                showEmailStatus('hide');
                return;
            }

            showEmailStatus('loading');

            fetch('{{ locale_route('brn.check-email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value ||
                            document.querySelector('meta[name="csrf-token"]')?.content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: email
                    }),
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        showEmailStatus('hide');
                        clearError(emailInput);
                        showError(emailInput, data.message);
                    } else {
                        showEmailStatus('success');
                        clearError(emailInput);
                        emailInput.style.border = '2px solid #10b981';
                        emailInput.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                    }
                })
                .catch(error => {
                    showEmailStatus('hide');
                });
        }

        // Auto-save form data on input changes
        function setupAutoSave() {
            const formElements = form.querySelectorAll('input, select, textarea');

            formElements.forEach(element => {
                element.addEventListener('input', saveFormData);
                element.addEventListener('change', saveFormData);
            });
        }

        // Initialize form persistence
        restoreFormData();
        setupAutoSave();

        // Clear saved data utility
        window.clearBrnFormData = function() {
            clearFormData();
            location.reload();
        };

        // Step navigation function
        function showStepByName(stepName) {
            const stepMapping = {
                'form1': 0,
                'form2': 1,
                'form3': 2,
                'form4': 3
            };

            const stepIndex = stepMapping[stepName];
            if (stepIndex !== undefined) {
                currentStep = stepIndex;
                showStep(currentStep);
            }
        }

        // Error handling functions
        function showError(input, message) {
            clearError(input);

            input.style.border = '2px solid #ef4444';
            input.style.backgroundColor = '#fef2f2';

            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#ef4444';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '4px';
            errorDiv.textContent = message;

            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }

        function clearError(input) {
            input.style.border = '';
            input.style.backgroundColor = '';

            const errorMsg = input.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }

        // Real-time email validation setup
        const emailInput = document.querySelector('input[name="email"]');
        let emailTimeout;

        if (emailInput) {
            emailInput.addEventListener('input', function() {
                clearTimeout(emailTimeout);
                const email = this.value.trim();

                clearError(this);

                if (email === '') {
                    showEmailStatus('hide');
                    return;
                }

                emailTimeout = setTimeout(() => {
                    validateEmailInDatabase(email);
                }, 500);
            });

            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && isValidEmail(email)) {
                    validateEmailInDatabase(email);
                }
            });
        }

        // CUSTOM SELECT AND CATEGORIES (existing code)
        /*************************************
         * GLOBAL CUSTOM SELECT HANDLER
         *************************************/
        const allCustomSelects = document.querySelectorAll(".custom-select");

        allCustomSelects.forEach((customSelect) => {
            const trigger = customSelect.querySelector(".custom-select-trigger");
            const text = customSelect.querySelector(".custom-select-text");
            const options = customSelect.querySelectorAll(".custom-option");
            const selectName = customSelect.dataset.select;
            const hiddenSelect = document.querySelector(`select[name="${getFieldName(selectName)}"]`);

            // Toggle dropdown open/close
            trigger.addEventListener("click", (e) => {
                e.stopPropagation();
                document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
                    if (openSelect !== customSelect) openSelect.classList.remove(
                        "open");
                });
                customSelect.classList.toggle("open");
            });

            // Handle option click
            options.forEach((option) => {
                option.addEventListener("click", (e) => {
                    e.stopPropagation();
                    const value = option.dataset.value;
                    const label = option.textContent;

                    // Update visible text + active state
                    text.textContent = label;
                    options.forEach((o) => o.classList.remove("active"));
                    option.classList.add("active");

                    // Sync to hidden select
                    if (hiddenSelect) {
                        hiddenSelect.value = value;
                    } else {
                        // console.warn(`Hidden select not found for ${selectName}`);
                    }

                    // Close dropdown
                    customSelect.classList.remove("open");

                    // If this is the "industry" select, trigger category update
                    if (selectName === "industry") {
                        updateCategories(value);
                    }

                    // Save form data after selection
                    saveFormData();
                });
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
            document.querySelectorAll(".custom-select.open").forEach((openSelect) => {
                if (!openSelect.contains(e.target)) openSelect.classList.remove("open");
            });
        });

        /*************************************
         * COUNTRY SEARCH FUNCTIONALITY
         *************************************/
        const countryDropdown = document.querySelector('[data-select="country"]');
        if (countryDropdown) {
            const countrySearchInput = countryDropdown.querySelector('.country-search');
            const countryOptionsContainer = countryDropdown.querySelector('.custom-options-scrollable');
            const countryOptions = countryOptionsContainer.querySelectorAll('.custom-option');

            // Handle search input
            if (countrySearchInput) {
                countrySearchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();

                    countryOptions.forEach(option => {
                        const countryName = option.textContent.toLowerCase();
                        if (countryName.includes(searchTerm)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });

                // Prevent dropdown from closing when clicking on search input
                countrySearchInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Clear search when dropdown closes
                countryDropdown.addEventListener('click', function() {
                    if (!countryDropdown.classList.contains('open')) {
                        countrySearchInput.value = '';
                        countryOptions.forEach(option => {
                            option.style.display = 'block';
                        });
                    }
                });

                // Focus search input when dropdown opens
                const countryTrigger = countryDropdown.querySelector('.custom-select-trigger');
                countryTrigger.addEventListener('click', function() {
                    setTimeout(() => {
                        if (countryDropdown.classList.contains('open')) {
                            countrySearchInput.focus();
                        }
                    }, 100);
                });
            }
        }

        /*************************************
         * INDUSTRY → CATEGORY HANDLER
         *************************************/
        const industryCategories = {
            agriculture: [
                "Crop Production",
                "Livestock Farming",
                "AgroTech",
                "Food Processing",
            ],
            manufacturing: [
                "Textile Production",
                "Automobile Manufacturing",
                "Electronics",
                "Pharmaceuticals",
            ],
            mining: [
                "Gold Mining",
                "Oil & Gas Exploration",
                "Quarrying",
                "Metal Refining",
            ],
            logistics: [
                "Freight Forwarding",
                "Warehousing",
                "Transportation",
                "Supply Chain Solutions",
            ],
        };

        const defaultTags = [
            "Agrobusiness",
            "Business Growth",
            "Sustainability",
            "Leadership",
        ];

        let selectedCategories = {}; // store selections by industry

        const categoryContainer = document.getElementById("categoryContainer");
        const industrySelect = document.querySelector('select[name="industry"]');
        const hiddenCategoryInput = document.getElementById("selectedCategories");

        // Initial render (default tags)
        renderTags(defaultTags, true);

        /** Update when user selects a new industry **/
        function updateCategories(industry) {
            categoryContainer.innerHTML = "";
            hiddenCategoryInput.value = "";

            if (!industry || !industryCategories[industry]) {
                renderTags(defaultTags, true);
                return;
            }

            const categories = industryCategories[industry];
            const saved = selectedCategories[industry] || [];

            renderTags(categories, false, saved);
        }

        /** Render tags **/
        function renderTags(categories, isDefault = false, saved = []) {
            categoryContainer.innerHTML = "";

            categories.forEach((category) => {
                const tag = document.createElement("div");
                tag.className = "interest-form-tag";
                if (saved.includes(category)) tag.classList.add("active");

                tag.innerHTML = `
      <span class="check-icon ${saved.includes(category) ? "active" : ""}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
        </svg>
      </span>
      <span>${category}</span>
    `;

                // Add click event
                tag.addEventListener("click", () => {
                    const currentIndustry = industrySelect.value;

                    // Toggle visual state
                    tag.classList.toggle("active");
                    const icon = tag.querySelector(".check-icon");
                    icon.classList.toggle("active");

                    // Handle storage
                    if (!isDefault && currentIndustry) {
                        if (!selectedCategories[currentIndustry]) {
                            selectedCategories[currentIndustry] = [];
                        }

                        if (tag.classList.contains("active")) {
                            selectedCategories[currentIndustry].push(category);
                        } else {
                            selectedCategories[currentIndustry] = selectedCategories[
                                currentIndustry
                            ].filter((c) => c !== category);
                        }
                    }

                    updateHiddenInput();
                    saveFormData(); // Save after category selection
                });

                categoryContainer.appendChild(tag);
            });
        }

        /** Sync hidden field for submission **/
        function updateHiddenInput() {
            const allSelected = Object.values(selectedCategories).flat();
            hiddenCategoryInput.value = allSelected.join(",");
        }


        // FORM STEPS (updated with validation)
        // Collect all forms and headings
        const formStages = document.querySelectorAll(
            ".first-stage-form-container, .second-stage-form-container, .third-stage-form-container, .fourth-stage-form-container"
        );

        const mobileHeadings = document.querySelectorAll(
            ".mobile-heading .first-form-heading, .mobile-heading .second-form-heading, .mobile-heading .third-form-heading, .mobile-heading .fourth-form-heading"
        );

        const desktopHeadings = document.querySelectorAll(
            ".desktop-heading .first-form-heading, .desktop-heading .second-form-heading, .desktop-heading .third-form-heading, .desktop-heading .fourth-form-heading"
        );

        const proceedButtons = document.querySelectorAll(
            ".interest-form-proceed-button"
        );
        const returnButtons = document.querySelectorAll(
            ".interest-form-return-button"
        );

        // Initialize currentStep from localStorage or default to 0
        function initializeCurrentStep() {
            const savedStep = localStorage.getItem(STEP_STORAGE_KEY);
            if (savedStep) {
                const stepMapping = {
                    'form1': 0,
                    'form2': 1,
                    'form3': 2,
                    'form4': 3
                };
                const stepIndex = stepMapping[savedStep] || 0;
                return stepIndex;
            }
            return 0;
        }

        let currentStep = initializeCurrentStep();
        const totalSteps = formStages.length;

        // Initialize form and heading display
        function showStep(step) {
            // Hide all stages
            formStages.forEach((form) => form.classList.add("hidden"));
            mobileHeadings.forEach((heading) => heading.classList.add("hidden"));
            desktopHeadings.forEach((heading) => heading.classList.add("hidden"));

            // Show current stage
            formStages[step].classList.remove("hidden");
            mobileHeadings[step].classList.remove("hidden");
            desktopHeadings[step].classList.remove("hidden");

            updateProgress(step);
            saveFormData(); // Save current step
        }

        // Update progress bar for all instances
        function updateProgress(step) {
            const progressSteppers = document.querySelectorAll(".progress-stepper");

            progressSteppers.forEach((stepper) => {
                const steps = stepper.querySelectorAll(".step");
                const progress = (step / totalSteps) * 100;
                stepper.style.setProperty("--progress-width", `${progress}%`);
                steps.forEach((s, i) => {
                    s.classList.remove("active", "completed");
                    if (i < step) s.classList.add("completed");
                    else if (i === step) s.classList.add("active");
                });
            });
        }

        // Handle navigation
        proceedButtons.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();

                // Validate current step before proceeding
                if (validateCurrentStep()) {
                    if (currentStep < totalSteps - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });
        });

        returnButtons.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        // Validation function for current step
        function validateCurrentStep() {
            const currentStage = formStages[currentStep];
            let isValid = true;

            // Clear previous errors
            currentStage.querySelectorAll('.error-message').forEach(msg => msg.remove());
            currentStage.querySelectorAll('input, select, textarea').forEach(input => {
                clearError(input);
            });

            if (currentStep === 0) {
                // Step 1: Basic Information validation
                const name = currentStage.querySelector('input[name="name"]');
                const email = currentStage.querySelector('input[name="email"]');
                const phone = currentStage.querySelector('input[name="phone"]');
                const position = currentStage.querySelector('select[name="position"]');
                const companyName = currentStage.querySelector('input[name="company_name"]');

                if (!name.value.trim()) {
                    showError(name, 'Full name is required');
                    isValid = false;
                }

                if (!email.value.trim()) {
                    showError(email, 'Email address is required');
                    isValid = false;
                } else if (!isValidEmail(email.value.trim())) {
                    showError(email, 'Please enter a valid email address');
                    isValid = false;
                }

                if (!phone.value.trim()) {
                    showError(phone, 'Phone number is required');
                    isValid = false;
                }

                if (!position.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="title"]');
                    showError(customSelect || position.parentNode, 'Please select your title');
                    isValid = false;
                }

                if (!companyName.value.trim()) {
                    showError(companyName, 'Company name is required');
                    isValid = false;
                }

            } else if (currentStep === 1) {
                // Step 2: Company Information validation
                const regNumber = currentStage.querySelector('input[name="reg_number"]');
                const country = currentStage.querySelector('select[name="country"]');
                const website = currentStage.querySelector('input[name="website"]');
                const linkedin = currentStage.querySelector('input[name="linkedin"]');
                const yearEstablished = currentStage.querySelector('input[name="year_established"]');

                if (!regNumber.value.trim()) {
                    showError(regNumber, 'Company registration number is required');
                    isValid = false;
                }

                if (!country.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="country"]');
                    showError(customSelect || country.parentNode, 'Country of registration is required');
                    isValid = false;
                }

                if (!website.value.trim()) {
                    showError(website, 'Company website or social handle is required');
                    isValid = false;
                }

                // if (!linkedin.value.trim()) {
                //     showError(linkedin, 'LinkedIn profile is required');
                //     isValid = false;
                // } else if (!linkedin.value.toLowerCase().includes('linkedin')) {
                //     showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                //     isValid = false;
                // }

                if (!linkedin.value.trim()) {
                    // showError(linkedin, 'LinkedIn profile is required');
                    isValid = true;
                } else if (!linkedin.value.toLowerCase().includes('linkedin')) {
                    showError(linkedin, 'Please enter a valid LinkedIn profile URL');
                    isValid = false;
                }

                if (!yearEstablished.value.trim()) {
                    showError(yearEstablished, 'Year established is required');
                    isValid = false;
                } else if (isNaN(yearEstablished.value) || yearEstablished.value < 1800 || yearEstablished
                    .value > new Date().getFullYear()) {
                    showError(yearEstablished, 'Please enter a valid year');
                    isValid = false;
                }

            } else if (currentStep === 2) {
                // Step 3: Industry Preference validation
                const industry = currentStage.querySelector('select[name="industry"]');
                const employees = currentStage.querySelector('select[name="number_of_employees"]');
                const revenue = currentStage.querySelector('select[name="years_of_operation"]');

                if (!industry.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="industry"]');
                    showError(customSelect || industry.parentNode, 'Please select your industry');
                    isValid = false;
                }

                if (!employees.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="employees"]');
                    showError(customSelect || employees.parentNode, 'Please select number of employees');
                    isValid = false;
                }

                if (!revenue.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="revenue"]');
                    showError(customSelect || revenue.parentNode, 'Please select revenue range');
                    isValid = false;
                }

            } else if (currentStep === 3) {
                // Step 4: Industry Insight validation
                const goals = currentStage.querySelector('textarea[name="goals"]');
                const selectedOutcome = currentStage.querySelector('select[name="selected_outcome"]');
                const b2bInterest = currentStage.querySelector('input[name="b2b_interest"]:checked');

                if (!goals.value.trim()) {
                    showError(goals, 'Please describe your company');
                    isValid = false;
                }

                if (!selectedOutcome.value.trim()) {
                    const customSelect = currentStage.querySelector('[data-select="interest"]');
                    showError(customSelect || selectedOutcome.parentNode, 'Please select your interest');
                    isValid = false;
                }

                if (!b2bInterest) {
                    // Show error for the radio button group
                    const radioGroup = currentStage.querySelector('.interest-form-option-buttons');
                    if (radioGroup) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.style.color = '#ef4444';
                        errorDiv.style.fontSize = '0.875rem';
                        errorDiv.style.marginTop = '4px';
                        errorDiv.style.textAlign = 'center';
                        errorDiv.textContent = 'Please select your B2B interest level';
                        radioGroup.parentNode.insertBefore(errorDiv, radioGroup.nextSibling);
                    }
                    isValid = false;
                }
            }

            // Scroll to first error if validation failed
            if (!isValid) {
                const firstError = currentStage.querySelector('.error-message');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            return isValid;
        }

        // Initialize first view
        showStep(currentStep);

        // Clear server errors when user starts typing
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                const serverError = this.parentNode.querySelector('.server-error');
                if (serverError) {
                    serverError.remove();
                    clearError(this);
                }
            });
        });

        // Form submission handling
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all steps before submission
            let allValid = true;
            for (let step = 0; step < totalSteps; step++) {
                const prevStep = currentStep;
                currentStep = step;
                if (!validateCurrentStep()) {
                    allValid = false;
                    showStep(step);
                    break;
                }
                currentStep = prevStep;
            }

            if (!allValid) {
                return;
            }

            // Disable submit button
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            const formData = new FormData(form);

            // IMPROVED: Get CSRF token with proper priority order
            let csrfToken = null;

            // 1. First priority: Form's hidden input (most reliable)
            const formTokenInput = form.querySelector('input[name="_token"]');
            if (formTokenInput && formTokenInput.value) {
                csrfToken = formTokenInput.value;
            }

            // 2. Second priority: Meta tag
            if (!csrfToken) {
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken && metaToken.content) {
                    csrfToken = metaToken.content;
                }
            }

            // 3. Third priority: Laravel's global object (if available)
            if (!csrfToken && typeof window.Laravel !== 'undefined') {
                csrfToken = window.Laravel.csrfToken;
            }

            // Verify token exists before submission
            if (!csrfToken) {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');

                responseMessage.textContent =
                    'Security error: CSRF token missing. Please refresh the page and try again.';
                responseMessage.style.color = '#dc2626';
                modal.show();
                return;
            }

            // Make the request with improved headers
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => {
                    // Handle token mismatch specifically
                    if (response.status === 419) {
                        throw {
                            status: 419,
                            message: 'Your session has expired. Please refresh the page and try again.'
                        };
                    }

                    if (!response.ok) {
                        return response.json().then(err => {
                            throw {
                                status: response.status,
                                data: err
                            };
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    responseMessage.textContent = data.message ||
                        'Your application was submitted successfully!';
                    responseMessage.style.color = '#059669';
                    modal.show();

                    // Clear saved form data
                    clearFormData();

                    // Reset form
                    form.reset();
                    currentStep = 0;
                    showStep(currentStep);

                    // Clear custom selects
                    document.querySelectorAll('.custom-select-text').forEach(text => {
                        const defaultText = text.closest('.custom-select').querySelector(
                            '.custom-option')?.textContent || 'Select option';
                        text.textContent = defaultText;
                    });
                    document.querySelectorAll('.custom-option').forEach(option => option.classList
                        .remove('active'));

                    // Clear categories
                    categoryContainer.innerHTML = '';
                    renderTags(defaultTags, true);
                    Object.keys(selectedCategories).forEach(key => delete selectedCategories[key]);
                    hiddenCategoryInput.value = '';
                })
                .catch(error => {
                    let errorMessage = 'Something went wrong. Please try again.';

                    if (error.status === 419) {
                        errorMessage =
                            'Your session has expired. Please refresh the page and try again.';
                    } else if (error.status === 422) {
                        if (error.data?.errors) {
                            showServerErrors(error.data.errors);
                            errorMessage = 'Please correct the errors below and try again.';
                        } else if (error.data?.message) {
                            errorMessage = error.data.message;
                        }
                    } else if (error.status === 403) {
                        errorMessage = 'Access denied. Please refresh the page and try again.';
                    } else if (error.status === 429) {
                        errorMessage = 'Too many attempts. Please wait a moment and try again.';
                    } else if (error.message) {
                        errorMessage = error.message;
                    }

                    responseMessage.textContent = errorMessage;
                    responseMessage.style.color = '#ef4444';
                    modal.show();
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.classList.remove('d-none');
                });
        });

        // Function to show server errors
        function showServerErrors(errors) {
            // Clear ALL previous errors thoroughly
            document.querySelectorAll('.error-message').forEach(msg => msg.remove());
            document.querySelectorAll('input, select, textarea').forEach(input => {
                clearError(input);
            });

            // Show field-specific errors
            Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorMessage = errors[field][0];
                    clearError(input);

                    input.style.border = '2px solid #ef4444';
                    input.style.backgroundColor = '#fef2f2';

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message server-error';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '4px';
                    errorDiv.textContent = errorMessage;

                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
            });

            // Scroll to first error
            const firstError = document.querySelector('.server-error');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        // LinkedIn URL normalization function
        function normalizeLinkedInURL(url) {
            if (!url || url.trim() === '') return '';

            let normalizedUrl = url.trim();

            // Remove common prefixes and normalize
            normalizedUrl = normalizedUrl.replace(/^(https?:\/\/)?(www\.)?/i, '');

            // Handle different LinkedIn domain variations
            normalizedUrl = normalizedUrl.replace(/^(linkedin\.com|linkedin\.in|in\.linkedin\.com)/i,
                'linkedin.com');

            // Ensure it starts with linkedin.com
            if (!normalizedUrl.toLowerCase().startsWith('linkedin.com')) {
                // If it looks like a LinkedIn profile path, prepend linkedin.com
                if (normalizedUrl.startsWith('/in/') || normalizedUrl.match(/^[a-zA-Z0-9\-_.]+$/)) {
                    normalizedUrl = 'linkedin.com/in/' + normalizedUrl.replace(/^\/in\//, '');
                } else {
                    // If it doesn't look like a valid LinkedIn URL, return as-is
                    return 'https://' + normalizedUrl;
                }
            }

            // Ensure /in/ path is present for profiles
            if (normalizedUrl.toLowerCase().startsWith('linkedin.com') &&
                !normalizedUrl.toLowerCase().includes('/company/') &&
                !normalizedUrl.toLowerCase().includes('/in/')) {
                const path = normalizedUrl.substring('linkedin.com'.length).replace(/^\/+/, '');
                if (path) {
                    normalizedUrl = 'linkedin.com/in/' + path;
                } else {
                    normalizedUrl = 'linkedin.com/in/';
                }
            }

            // Add https prefix
            return 'https://' + normalizedUrl;
        }

        // Website URL normalization function
        function normalizeWebsiteURL(url) {
            if (!url || url.trim() === '') return '';

            let normalizedUrl = url.trim();

            // If it already has a protocol, return as-is
            if (/^https?:\/\//i.test(normalizedUrl)) {
                return normalizedUrl;
            }

            // Remove www. if present at the beginning
            normalizedUrl = normalizedUrl.replace(/^www\./i, '');

            // Check if it looks like a social media handle (starts with @)
            if (normalizedUrl.startsWith('@')) {
                // Keep social handles as-is
                return normalizedUrl;
            }

            // Check if it's a social media URL pattern
            const socialPatterns = [
                /^(twitter\.com|x\.com)\//i,
                /^instagram\.com\//i,
                /^facebook\.com\//i,
                /^tiktok\.com\//i,
                /^youtube\.com\//i,
                /^linkedin\.com\//i
            ];

            let isSocialUrl = socialPatterns.some(pattern => pattern.test(normalizedUrl));

            // If it's not a social URL and doesn't have a domain extension, 
            // assume it's a domain and add .com
            if (!isSocialUrl && !normalizedUrl.includes('.') && !normalizedUrl.startsWith('@')) {
                normalizedUrl += '.com';
            }

            // Add https prefix for URLs
            if (!normalizedUrl.startsWith('@')) {
                return 'https://' + normalizedUrl;
            }

            return normalizedUrl;
        }

        // LinkedIn URL auto-formatting
        const linkedinInput = document.querySelector('input[name="linkedin"]');
        if (linkedinInput) {
            linkedinInput.addEventListener('blur', function() {
                const currentValue = this.value.trim();
                if (currentValue && currentValue.toLowerCase().includes('linkedin')) {
                    const normalizedValue = normalizeLinkedInURL(currentValue);
                    if (normalizedValue !== currentValue) {
                        this.value = normalizedValue;
                        // Trigger change event to save the updated value
                        this.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        // Also trigger input event for any other listeners
                        this.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                } else if (currentValue) {
                    // If user entered something that doesn't include 'linkedin', 
                    // assume it's a username and format it
                    const normalizedValue = normalizeLinkedInURL(currentValue);
                    this.value = normalizedValue;
                    this.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                    this.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                }
            });

            // Also format on paste
            linkedinInput.addEventListener('paste', function() {
                setTimeout(() => {
                    const currentValue = this.value.trim();
                    if (currentValue) {
                        const normalizedValue = normalizeLinkedInURL(currentValue);
                        if (normalizedValue !== currentValue) {
                            this.value = normalizedValue;
                            this.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                            this.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                        }
                    }
                }, 100);
            });
        }

        // Website URL auto-formatting
        const websiteInput = document.querySelector('input[name="website"]');
        if (websiteInput) {
            websiteInput.addEventListener('blur', function() {
                const currentValue = this.value.trim();
                if (currentValue) {
                    const normalizedValue = normalizeWebsiteURL(currentValue);
                    if (normalizedValue !== currentValue) {
                        this.value = normalizedValue;
                        // Trigger change event to save the updated value
                        this.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        // Also trigger input event for any other listeners
                        this.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                }
            });

            // Also format on paste
            websiteInput.addEventListener('paste', function() {
                setTimeout(() => {
                    const currentValue = this.value.trim();
                    if (currentValue) {
                        const normalizedValue = normalizeWebsiteURL(currentValue);
                        if (normalizedValue !== currentValue) {
                            this.value = normalizedValue;
                            this.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                            this.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                        }
                    }
                }, 100);
            });
        }

    });
</script>
