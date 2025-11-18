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

                        const isRequired = ['name', 'email'].includes(field);
                        const placeholder = fieldLabels[field] || field;

                        if (field === 'expectations') {
                            fieldDiv.innerHTML = `
                            <textarea name="${field}" 
                                   class="form-control mb-4" 
                                   placeholder="${placeholder}" 
                                   rows="3"
                                   ${isRequired ? 'required' : ''}></textarea>
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
            <input type="text" name="phone" class="form-control mb-4" placeholder="Phone no." required>
        `;
        }

        seatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentEventId) {
                alert("Event ID not found. Please try again.");
                return;
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
