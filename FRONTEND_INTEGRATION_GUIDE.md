# Frontend JavaScript Updates for Google Calendar Integration

## Required Changes to schedule-consultation.blade.php

### 1. Update Modal HTML (around line 1469-1475)

Replace the existing modal buttons with:

```html
<a
    href="#"
    id="downloadIcsLink"
    class="submit-btn-modal"
    style="display: none;"
>
    Download Calendar File (.ics)
</a>
<a
    href="#"
    id="openCalendarLink"
    class="submit-btn-modal"
    style="display: none;"
>
    Open in Google Calendar
</a>
<a
    href="#"
    id="meetLinkButton"
    class="submit-btn-modal"
    style="display: none;"
    target="_blank"
>
    Join Google Meet
</a>
<a href="{{ route('homepage') }}" class="submit-btn-modal">
    Return to Homepage
</a>
```

### 2. Update Submit Button Handler (around line 2412-2500)

Replace the existing submit button logic with:

```javascript
document.querySelectorAll(".submit-schedule").forEach((btn) => {
    // Track which button was last clicked
    btn.addEventListener("click", () => {
        document
            .querySelectorAll(".submit-schedule")
            .forEach((b) => b.classList.remove("last-clicked"));
        btn.classList.add("last-clicked");
    });

    // Main submit action
    btn.onclick = () => {
        if (!selectedDate) {
            flashMessage("Please select a date first");
            return;
        }

        const hour = timeWheels.hour.items[timeWheels.hour.currentIndex];
        const minute = timeWheels.minute.items[timeWheels.minute.currentIndex];
        const second = timeWheels.second.items[timeWheels.second.currentIndex];
        const ampm = timeWheels.ampm.items[timeWheels.ampm.currentIndex];

        // Format the date for the form submission (YYYY-MM-DD)
        const formattedDate =
            selectedDate.getFullYear() +
            "-" +
            String(selectedDate.getMonth() + 1).padStart(2, "0") +
            "-" +
            String(selectedDate.getDate()).padStart(2, "0");

        // Convert to 24-hour format for form submission
        let hour24 = parseInt(hour);
        if (ampm === "PM" && hour24 !== 12) {
            hour24 += 12;
        } else if (ampm === "AM" && hour24 === 12) {
            hour24 = 0;
        }

        const formattedTime = String(hour24).padStart(2, "0") + ":" + minute;
        const timezone = timezoneDropdown.getValue();

        // Submit via fetch API
        fetch('{{ route("consultation.schedule.submit") }}', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
                Accept: "application/json",
            },
            body: JSON.stringify({
                session_date: formattedDate,
                session_time: formattedTime,
                timezone: timezone,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Update modal with session details
                    modalDateText.textContent =
                        "Date: " + selectedDate.toDateString();
                    modalTimeText.textContent =
                        "Time: " +
                        hour +
                        ":" +
                        minute +
                        ":" +
                        second +
                        " " +
                        ampm +
                        " " +
                        timezone;

                    // Show Meet link if available
                    if (data.meet_link) {
                        const meetLinkButton =
                            document.getElementById("meetLinkButton");
                        meetLinkButton.href = data.meet_link;
                        meetLinkButton.style.display = "block";
                    }

                    // Show calendar link if available
                    if (data.calendar_link) {
                        const calendarLinkButton =
                            document.getElementById("openCalendarLink");
                        calendarLinkButton.href = data.calendar_link;
                        calendarLinkButton.style.display = "block";
                    }

                    // Show ICS download link if available
                    if (data.ics_download_url) {
                        const icsLinkButton =
                            document.getElementById("downloadIcsLink");
                        icsLinkButton.href = data.ics_download_url;
                        icsLinkButton.style.display = "block";
                    }

                    // Show modal
                    modal.style.display = "flex";
                } else {
                    flashMessage(
                        data.message || "An error occurred. Please try again."
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                flashMessage("An error occurred. Please try again.");
            });
    };
});
```

### 3. Add CSRF Token Meta Tag (in <head> section)

Ensure you have this meta tag in the head section:

```html
<meta name="csrf-token" content="{{ csrf_token() }}" />
```

### 4. Remove Old Modal Button Handlers (around line 2559-2574)

Remove or comment out the old button handlers that reference "Open customer" and "Return to Homepage".

## Testing the Integration

1. **Complete the consultation form and payment**
2. **Navigate to the scheduling page**
3. **Select a date and time**
4. **Click "Submit Schedule"**
5. **Verify the modal shows:**
    - Date and time
    - Google Meet join link
    - Open in Google Calendar button
    - Download .ics file button
    - Return to Homepage button

## Expected Behavior

-   ✅ Google Calendar event created automatically
-   ✅ Meet link generated and displayed
-   ✅ Email invitations sent to both admin and client
-   ✅ .ics file available for download
-   ✅ Direct link to view event in Google Calendar

## Troubleshooting

If Meet link doesn't appear:

1. Check browser console for errors
2. Verify Google Calendar API is authorized
3. Check Laravel logs in `storage/logs/laravel.log`
4. Ensure `ADMIN_EMAIL` is set in `.env`
