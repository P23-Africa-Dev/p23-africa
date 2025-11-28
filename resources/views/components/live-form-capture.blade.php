@props([
    'formId' => 'live-capture-form',
    'saveUrl' => route('contact.save-draft'),
    'formSelector' => null, // Optional: custom form selector
    'saveInterval' => 2000, // Save every 2 seconds
    'enableLogging' => true, // Enable console logging for debug
])

<div x-data="liveFormCapture({
    formId: '{{ $formId }}',
    saveUrl: '{{ $saveUrl }}',
    formSelector: {{ $formSelector ? "'{$formSelector}'" : 'null' }},
    saveInterval: {{ $saveInterval }},
    enableLogging: {{ $enableLogging ? 'true' : 'false' }}
})" x-init="init()">

    <!-- Live Capture Status Indicator (only show if there's activity) -->
    {{-- <div x-show="isActive" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="fixed top-4 right-4 z-50 flex items-center space-x-2 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-lg shadow-lg text-sm">

        <!-- Status Icon -->
        <div class="flex items-center space-x-1">
            <svg x-show="status === 'saving'" class="animate-spin" width="16" height="16" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>

            <svg x-show="status === 'saved'" width="16" height="16" class="text-green-600" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>

            <svg x-show="status === 'error'" width="16" height="16" class="text-red-600" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>

        <!-- Status Text -->
        <span x-text="statusMessage"></span>

        <!-- Keystroke Counter (optional debug info) -->
        <span x-show="enableLogging && keystrokeCount > 0" class="text-xs opacity-75">
            (<span x-text="keystrokeCount"></span> keystrokes)
        </span>
    </div> --}}

    <!-- Hidden Session ID field for the form -->
    {{ $slot }}
</div>

<script>
    function liveFormCapture(config) {
        return {
            // Configuration
            formId: config.formId,
            saveUrl: config.saveUrl,
            formSelector: config.formSelector,
            saveInterval: config.saveInterval,
            enableLogging: config.enableLogging,

            // State
            form: null,
            sessionId: null,
            keystrokeCount: 0,
            lastSavedData: '',
            saveTimer: null,
            isActive: false,
            status: 'idle', // idle, saving, saved, error
            statusMessage: '',
            fieldInteractions: {},
            isSubmitted: false, // Flag to prevent further captures after submission

            init() {
                this.log('Initializing Live Form Capture...');

                // Generate or get session ID
                this.sessionId = this.getOrCreateSessionId();

                // Find the form
                this.form = this.formSelector ?
                    document.querySelector(this.formSelector) :
                    document.getElementById(this.formId) || document.querySelector('form');

                if (!this.form) {
                    this.log('ERROR: Form not found!');
                    return;
                }

                this.log('Form found:', this.form);

                // Add session ID to form if not exists
                this.addSessionIdToForm();

                // Set up event listeners
                this.setupEventListeners();

                // Initial save to create draft record
                this.scheduleSave();
            },

            getOrCreateSessionId() {
                let sessionId = sessionStorage.getItem('live_form_session_id');
                if (!sessionId) {
                    sessionId = 'lfc_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                    sessionStorage.setItem('live_form_session_id', sessionId);
                }
                return sessionId;
            },

            addSessionIdToForm() {
                // Check if session_id input already exists
                let sessionInput = this.form.querySelector('input[name="session_id"]');

                if (sessionInput) {
                    // Update existing input's value
                    sessionInput.value = this.sessionId;
                    this.log('Updated existing session_id input with value:', this.sessionId);
                } else {
                    // Create new hidden input if it doesn't exist
                    sessionInput = document.createElement('input');
                    sessionInput.type = 'hidden';
                    sessionInput.name = 'session_id';
                    sessionInput.value = this.sessionId;
                    this.form.appendChild(sessionInput);
                    this.log('Created new session_id input with value:', this.sessionId);
                }
            },

            setupEventListeners() {
                // Find ALL inputs including hidden ones in multi-step forms
                const inputs = this.form.querySelectorAll('input, textarea, select');

                this.log(`Found ${inputs.length} form inputs to monitor`);

                inputs.forEach((input, index) => {
                    this.log(
                        `Setting up listeners for input ${index + 1}: ${input.name || input.id || input.type}`
                    );

                    // Track field interactions
                    input.addEventListener('focus', (e) => {
                        this.trackFieldInteraction(e.target.name || e.target.id, 'focus');
                        this.log(`Focus event on: ${e.target.name || e.target.id}`);
                    });

                    input.addEventListener('blur', (e) => {
                        if (this.isSubmitted) return; // Don't capture after submission

                        this.trackFieldInteraction(e.target.name || e.target.id, 'blur');
                        this.scheduleSave(); // Save on blur
                        this.log(`Blur event on: ${e.target.name || e.target.id}, scheduling save`);
                    });

                    // Track keystrokes and changes
                    input.addEventListener('input', (e) => {
                        if (this.isSubmitted) return; // Don't capture after submission

                        this.keystrokeCount++;
                        this.isActive = true;
                        this.trackFieldInteraction(e.target.name || e.target.id, 'input');
                        this.scheduleSave();
                        this.log(
                            `Input event on: ${e.target.name || e.target.id}, keystroke count: ${this.keystrokeCount}`
                        );
                    });

                    // Track selections for select/radio/checkbox
                    input.addEventListener('change', (e) => {
                        if (this.isSubmitted) return; // Don't capture after submission

                        this.trackFieldInteraction(e.target.name || e.target.id, 'change');
                        this.scheduleSave();
                        this.log(
                            `Change event on: ${e.target.name || e.target.id}, value: ${e.target.value}`
                        );
                    });
                });

                // Special handling for multi-step forms - monitor step changes
                const stepButtons = this.form.querySelectorAll('.next-btn, .back-btn, [class*="step"]');
                stepButtons.forEach(button => {
                    if (button.tagName === 'BUTTON' || button.tagName === 'A') {
                        button.addEventListener('click', () => {
                            this.log('Step change detected, scheduling save');
                            this.scheduleSave();
                        });
                    }
                });

                // Hook into form submission but don't prevent it initially
                // Let the form's own validation run first
                this.setupSubmissionInterception();
            },

            trackFieldInteraction(fieldName, eventType) {
                if (!fieldName) return;

                if (!this.fieldInteractions[fieldName]) {
                    this.fieldInteractions[fieldName] = [];
                }

                this.fieldInteractions[fieldName].push({
                    event: eventType,
                    timestamp: new Date().toISOString()
                });
            },

            scheduleSave() {
                // Clear existing timer
                if (this.saveTimer) {
                    clearTimeout(this.saveTimer);
                }

                // Schedule new save
                this.saveTimer = setTimeout(() => {
                    this.saveFormData();
                }, this.saveInterval);
            },

            async saveFormData(isSubmission = false) {
                // Don't save if already submitted
                if (this.isSubmitted && !isSubmission) {
                    this.log('Skipping save - form already submitted');
                    return;
                }

                this.log('Starting form data save...', {
                    isSubmission
                });

                const formData = new FormData(this.form);
                const formObject = {};

                // Convert FormData to object
                for (let [key, value] of formData.entries()) {
                    if (formObject[key]) {
                        // Handle multiple values (like checkboxes)
                        if (Array.isArray(formObject[key])) {
                            formObject[key].push(value);
                        } else {
                            formObject[key] = [formObject[key], value];
                        }
                    } else {
                        formObject[key] = value;
                    }
                }

                // Add metadata
                formObject.session_id = this.sessionId;
                formObject.keystroke_count = this.keystrokeCount;
                formObject.field_interactions = this.fieldInteractions;
                formObject.is_submission = isSubmission;

                this.log('Form data to save:', formObject);

                // Skip save if data hasn't changed (unless it's a submission)
                const currentData = JSON.stringify(formObject);
                if (!isSubmission && currentData === this.lastSavedData) {
                    this.log('Skipping save - no data changes detected');
                    return;
                }

                this.status = 'saving';
                this.statusMessage = 'Saving draft...';
                this.log('Sending save request to:', this.saveUrl);

                try {
                    const response = await fetch(this.saveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formObject)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        this.status = 'saved';
                        this.statusMessage = isSubmission ? 'Form submitted!' : 'Draft saved';
                        this.lastSavedData = currentData;

                        this.log('Form data saved successfully');

                        if (isSubmission) {
                            // Handle successful form submission
                            this.handleSuccessfulSubmission(result);
                        } else {
                            // Hide status after 3 seconds for draft saves
                            setTimeout(() => {
                                this.status = 'idle';
                                this.isActive = false;
                            }, 3000);
                        }
                    } else {
                        throw new Error(result.message || 'Save failed');
                    }
                } catch (error) {
                    this.status = 'error';
                    this.statusMessage = 'Save failed';
                    this.log('ERROR saving form data:', error);

                    // Hide error status after 5 seconds
                    setTimeout(() => {
                        this.status = 'idle';
                    }, 5000);
                }
            },

            setupSubmissionInterception() {
                // Instead of intercepting form submit, we'll intercept the successful fetch request
                // Set up a global hook that the contact form can call
                window.liveFormCaptureInstances = window.liveFormCaptureInstances || [];
                window.liveFormCaptureInstances.push(this);

                // Provide global methods for form integration
                window.notifyLiveFormCapture = (action, data) => {
                    window.liveFormCaptureInstances.forEach(instance => {
                        if (instance.form && (instance.form.id === this.form.id || instance.form === this
                                .form)) {
                            instance.handleFormAction(action, data);
                        }
                    });
                };
            },

            handleFormAction(action, data) {
                if (action === 'validationPassed') {
                    this.log('Form validation passed - marking as submitted');
                    this.saveFormData(true); // Mark as submitted
                } else if (action === 'submissionSuccess') {
                    this.handleSuccessfulSubmission(data);
                } else if (action === 'submissionError') {
                    this.log('Form submission failed');
                    // Don't reset the component, keep the draft
                }
            },

            handleSuccessfulSubmission(result) {
                // Mark as submitted to prevent further captures
                this.isSubmitted = true;

                // Clear any pending save timers
                if (this.saveTimer) {
                    clearTimeout(this.saveTimer);
                }

                // Clear session storage since form is submitted
                sessionStorage.removeItem('live_form_session_id');

                // Show success popup (since we're handling the success now)
                if (typeof window.showPopup === 'function') {
                    window.showPopup('success', 'Message Sent!',
                        result.message || 'Thank you for contacting us. We will get back to you soon!');
                }

                // Reset form UI elements
                const form = this.form;
                const hiddenInput = document.getElementById('selected-services');

                form.reset();
                if (hiddenInput) hiddenInput.value = '';

                const recaptchaToken = document.getElementById('recaptchaToken');
                if (recaptchaToken) recaptchaToken.value = '';

                // Reset service tags
                document.querySelectorAll('.tag.selected').forEach(tag => {
                    tag.classList.remove('selected');
                    const icon = tag.querySelector('.tag-icon');
                    if (icon) {
                        icon.src = '/img/unselected.png'; // Use relative path
                    }
                });

                // Reset slider
                const rangeInput = document.getElementById("priceRange");
                const rangeValue = document.getElementById("rangeValue");
                if (rangeInput && rangeValue) {
                    rangeInput.value = '';
                    if (typeof window.updateSlider === 'function') {
                        window.updateSlider();
                    }
                }

                // Reset component state
                this.keystrokeCount = 0;
                this.fieldInteractions = {};
                this.isActive = false;
                this.status = 'idle';

                this.log('Form submission completed and state reset');
            },
            log(...args) {
                if (this.enableLogging) {
                    console.log('[LiveFormCapture]', ...args);
                }
            }
        }
    }
</script>

<style>
    /* Additional styles for the live capture indicator */
    .live-capture-indicator {
        transition: all 0.3s ease;
    }
</style>
