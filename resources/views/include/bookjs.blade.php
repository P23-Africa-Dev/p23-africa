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

        seatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading spinner
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;

            const formData = new FormData(seatForm);

            fetch("{{ route('seats.store', $event->id) }}", {
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

                    if (data.success) {
                        alert('Something went wrong. Please try again.');
                    } else {
                        seatForm.reset();
                        const seatModal = bootstrap.Modal.getInstance(document.getElementById(
                            'seatModal'));
                        seatModal.hide();

                        const successModal = new bootstrap.Modal(document.getElementById(
                            'successModal'));
                        successModal.show();
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
