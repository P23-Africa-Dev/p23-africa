<script>
    //Access Live Event Page
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('actionBtn');
        const eventDateTime = new Date(btn.dataset.eventDatetime);
        const eventLink = btn.dataset.eventLink;

        function updateButtonState() {
            const now = new Date();
            if (now >= eventDateTime) {
                btn.textContent = "Join Event";
                btn.classList.remove('btn-color');
                btn.classList.add('btn-primary');

                btn.removeAttribute('data-bs-toggle', 'modal');
                btn.removeAttribute('data-bs-target', '#joinEventModal');

                // Add click-to-redirect
                btn.addEventListener('click', function() {
                    window.open(eventLink, '_blank');
                }, {
                    once: true
                });
            }
        }

        updateButtonState();
        setInterval(updateButtonState, 60000);
    });


    // Pop Up
    document.addEventListener('DOMContentLoaded', function() {
        const seatForm = document.getElementById('seatForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');
        const eventIdInput = document.getElementById('event_id');
        let currentEventId = null;

        // Attach click listener to all "Book A Seat" buttons
        document.querySelectorAll('.actionBtn').forEach(button => {
            button.addEventListener('click', function() {
                currentEventId = this.getAttribute('data-event-id');
                eventIdInput.value = currentEventId;

                // Load dynamic fields based on event
                loadBookingFields(currentEventId);
            });
        });

        // Function to load booking fields dynamically
        function loadBookingFields(eventId) {
            fetch(`/api/event/${eventId}/booking-fields`)
                .then(response => response.json())
                .then(data => {
                    const dynamicFieldsContainer = document.getElementById('dynamic-fields');
                    dynamicFieldsContainer.innerHTML = '';

                    const fieldLabels = {
                        'name': 'Full name',
                        'email': 'Email',
                        'phone': 'Phone no.',
                        'job_title': 'Job Title',
                        'organization': 'Organization',
                        'location': 'Location',
                        'industry': 'Industry',
                        'expectations': 'What are your expectations for this event?',
                        'referral_source': 'How did you hear about us?'
                    };

                    data.fields.forEach(field => {
                        const fieldDiv = document.createElement('div');
                        fieldDiv.className = 'mb-3';

                        const isRequired = ['name', 'email', 'phone'].includes(field);
                        const placeholder = fieldLabels[field] || field;

                        if (field === 'expectations') {
                            fieldDiv.innerHTML = `
                            <textarea name="${field}" 
                                   class="form-control mb-4" 
                                   placeholder="${placeholder}" 
                                   rows="3"
                                   ${isRequired ? 'required' : ''}></textarea>
                        `;
                        } else if (field === 'phone') {
                            fieldDiv.innerHTML = `
                            <div class="phone-input-group mb-4" style="display: flex; align-items: center; gap: 8px;">
                                <select class="form-select country-code-select" id="countryCode_${field}" style="max-width: 120px; flex-shrink: 0;">
                                    <option value="+1" data-flag="us">🇺🇸 +1</option>
                                    <option value="+1" data-flag="ca">🇨🇦 +1</option>
                                    <option value="+44" data-flag="gb" selected>🇬🇧 +44</option>
                                    <option value="+27" data-flag="za">🇿🇦 +27</option>
                                    <option value="+234" data-flag="ng">🇳🇬 +234</option>
                                    <option value="+254" data-flag="ke">🇰🇪 +254</option>
                                    <option value="+233" data-flag="gh">🇬🇭 +233</option>
                                    <option value="+255" data-flag="tz">🇹🇿 +255</option>
                                    <option value="+256" data-flag="ug">🇺🇬 +256</option>
                                    <option value="+20" data-flag="eg">🇪🇬 +20</option>
                                    <option value="+212" data-flag="ma">🇲🇦 +212</option>
                                    <option value="+216" data-flag="tn">🇹🇳 +216</option>
                                    <option value="+213" data-flag="dz">🇩🇿 +213</option>
                                    <option value="+250" data-flag="rw">🇷🇼 +250</option>
                                    <option value="+91" data-flag="in">🇮🇳 +91</option>
                                    <option value="+61" data-flag="au">🇦🇺 +61</option>
                                    <option value="+86" data-flag="cn">🇨🇳 +86</option>
                                    <option value="+971" data-flag="ae">🇦🇪 +971</option>
                                    <option value="+33" data-flag="fr">🇫🇷 +33</option>
                                    <option value="+49" data-flag="de">🇩🇪 +49</option>
                                </select>
                                <input type="tel" 
                                       name="${field}" 
                                       class="form-control phone-input" 
                                       placeholder="Enter phone number" 
                                       style="flex: 1;"
                                       ${isRequired ? 'required' : ''}>
                            </div>
                        `;
                        } else {
                            let inputType = 'text';
                            if (field === 'email') inputType = 'email';

                            fieldDiv.innerHTML = `
                            <input type="${inputType}" 
                                   name="${field}" 
                                   class="form-control mb-4" 
                                   placeholder="${placeholder}" 
                                   ${isRequired ? 'required' : ''}>
                        `;
                        }

                        dynamicFieldsContainer.appendChild(fieldDiv);
                    });
                })
                .catch(error => {
                    console.error('Error loading booking fields:', error);
                    // Fallback to default fields
                    loadDefaultFields();
                });
        }

        // Fallback function for default fields
        function loadDefaultFields() {
            const dynamicFieldsContainer = document.getElementById('dynamic-fields');
            dynamicFieldsContainer.innerHTML = `
            <input type="text" name="name" class="form-control mb-4" placeholder="Full name" required>
            <input type="email" name="email" class="form-control mb-4" placeholder="Email" required>
            <div class="phone-input-group mb-4" style="display: flex; align-items: center; gap: 8px;">
                <select class="form-select country-code-select" id="countryCode_phone" style="max-width: 120px; flex-shrink: 0;">
                    <option value="+1" data-flag="us">🇺🇸 +1</option>
                    <option value="+1" data-flag="ca">🇨🇦 +1</option>
                    <option value="+44" data-flag="gb" selected>🇬🇧 +44</option>
                    <option value="+27" data-flag="za">🇿🇦 +27</option>
                    <option value="+234" data-flag="ng">🇳🇬 +234</option>
                    <option value="+254" data-flag="ke">🇰🇪 +254</option>
                    <option value="+233" data-flag="gh">🇬🇭 +233</option>
                    <option value="+255" data-flag="tz">🇹🇿 +255</option>
                    <option value="+256" data-flag="ug">🇺🇬 +256</option>
                    <option value="+20" data-flag="eg">🇪🇬 +20</option>
                    <option value="+212" data-flag="ma">🇲🇦 +212</option>
                    <option value="+216" data-flag="tn">🇹🇳 +216</option>
                    <option value="+213" data-flag="dz">🇩🇿 +213</option>
                    <option value="+250" data-flag="rw">🇷🇼 +250</option>
                    <option value="+91" data-flag="in">🇮🇳 +91</option>
                    <option value="+61" data-flag="au">🇦🇺 +61</option>
                    <option value="+86" data-flag="cn">🇨🇳 +86</option>
                    <option value="+971" data-flag="ae">🇦🇪 +971</option>
                    <option value="+33" data-flag="fr">🇫🇷 +33</option>
                    <option value="+49" data-flag="de">🇩🇪 +49</option>
                </select>
                <input type="tel" name="phone" class="form-control phone-input" placeholder="Enter phone number" style="flex: 1;" required>
            </div>
        `;
        }

        seatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentEventId) {
                alert("Event ID not found. Please try again.");
                return;
            }

            // Combine country code with phone number before submission
            const phoneInput = seatForm.querySelector('input[name="phone"]');
            const countryCodeSelect = seatForm.querySelector('.country-code-select');

            if (phoneInput && countryCodeSelect) {
                const countryCode = countryCodeSelect.value;
                const phoneNumber = phoneInput.value.trim();

                // Remove any existing country code from phone number to avoid duplication
                let cleanPhoneNumber = phoneNumber;
                if (phoneNumber.startsWith('+')) {
                    // Extract just the number part after the country code
                    const phoneRegex = /^\+\d{1,4}/;
                    cleanPhoneNumber = phoneNumber.replace(phoneRegex, '');
                }

                if (cleanPhoneNumber) {
                    // Combine country code and phone number
                    phoneInput.value = countryCode + cleanPhoneNumber;
                    console.log('Combined phone number:', phoneInput.value); // Debug log
                }
            }

            spinner.classList.remove('d-none');
            submitBtn.disabled = true;

            const formData = new FormData(seatForm);

            fetch(`/book-seat/${currentEventId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    spinner.classList.add('d-none');
                    submitBtn.disabled = false;

                    if (data.success === false) {
                        alert('Something went wrong. Please try again.');
                    } else {
                        seatForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById('seatModal')).hide();
                        new bootstrap.Modal(document.getElementById('successModal')).show();
                    }
                })
                .catch(error => {
                    console.error(error);
                    spinner.classList.add('d-none');
                    submitBtn.disabled = false;
                    alert('Error submitting the form.');
                });
        });
    });
</script>
