    document.addEventListener('DOMContentLoaded', function () {
        function getFormattedDate(date) {
            const day = date.getDate();
            const suffix = (d => {
                if (d > 3 && d < 21) return 'th';
                switch (d % 10) {
                    case 1: return 'st';
                    case 2: return 'nd';
                    case 3: return 'rd';
                    default: return 'th';
                }
            })(day);

            const month = date.toLocaleString('default', { month: 'long' });
            const year = date.getFullYear();
            return `${day}${suffix} ${month}, ${year}`;
        }

        function updateCountdowns() {
            const countdownElements = document.querySelectorAll('.countdown');
            countdownElements.forEach(el => {
                const dateTime = el.dataset.eventDatetime;
                const eventDate = new Date(dateTime);
                const countDownDate = eventDate.getTime();

                const now = new Date();
                const today = now.toISOString().split('T')[0];
                const eventDateOnly = eventDate.toISOString().split('T')[0];
                const distance = countDownDate - now.getTime();

                if (today === eventDateOnly) {
                    if (distance < 0) {
                        el.innerHTML = "Event Started";
                        return;
                    }
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    el.innerHTML = `Happening in ${hours}h ${minutes}m`;
                } else {
                    el.innerHTML = getFormattedDate(eventDate);
                }
            });
        }

        updateCountdowns();
        setInterval(updateCountdowns, 60000);
    });

    // Events Filter + Search
    $(document).ready(function () {
        const $eventList = $('#event-list');

        function fetchEvents(term = '', filter = '') {
            $.ajax({
                url: '{{ route('events.search') }}',
                data: { term, filter },
                success: function (data) {
                    $eventList.empty();

                    if (data.length === 0) {
                        $eventList.append('<div class="text-muted text-center w-100">No events found.</div>');
                    } else {
                        data.forEach(event => {
                            const desc = event.description.length > 233 ? event.description.slice(0, 230) + '...' : event.description;
                            const subtitle = event.subtitle ? `<i>(${event.subtitle})</i>` : '';

                            const html = `
                                <div class="event-card">
                                    <div>
                                        <h3>
                                            <img src="{{ asset('images/calendar.png') }}" loading="lazy" alt="">
                                            <a href="/events/${event.slug}">
                                                ${event.title} ${subtitle}
                                            </a>
                                        </h3>
                                        <p>${desc}</p>
                                        <div class="event-card-footer">
                                            <span class="countdown status" data-event-datetime="${event.event_date} ${event.event_time}">
                                                Loading...
                                            </span>
                                            <span class="time">${event.event_time} - 5:30PM</span>
                                            <button class="btn">Book A Seat →</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $eventList.append(html);
                        });

                        // Re-run countdown updater after injecting new HTML
                        setTimeout(() => {
                            const event = new Event('DOMContentLoaded');
                            document.dispatchEvent(event);
                        }, 200);
                    }
                },
                error: function () {
                    $eventList.html('<div class="text-danger text-center w-100">Error loading events.</div>');
                }
            });
        }

        // Live Search
        $('#search-input').on('input', function () {
            let term = $(this).val();
            fetchEvents(term);
        });

        // Filter Buttons
        $('.filter-btn').on('click', function () {
            let filter = $(this).data('filter');
            $('#search-input').val(''); // Clear input
            fetchEvents('', filter);
        });
    });