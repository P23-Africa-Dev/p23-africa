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
document.addEventListener('DOMContentLoaded', function () {
    const seatForm = document.getElementById('seatForm');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('submitSpinner');
    const eventIdInput = document.getElementById('event_id');
    let currentEventId = null;

    // Attach click listener to all "Book A Seat" buttons
    document.querySelectorAll('.actionBtn').forEach(button => {
        button.addEventListener('click', function () {
            currentEventId = this.getAttribute('data-event-id');
            eventIdInput.value = currentEventId;
        });
    });

    seatForm.addEventListener('submit', function (e) {
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
