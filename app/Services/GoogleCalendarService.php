<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\ConferenceSolutionKey;
use Google\Service\Calendar\EventAttendee;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setApplicationName(config('app.name'));
        $this->client->setScopes([Calendar::CALENDAR, Calendar::CALENDAR_EVENTS]);
        $this->client->setAuthConfig(storage_path('app/google-calendar-credentials.json'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        // Load previously authorized token from storage if available
        $tokenPath = storage_path('app/google-calendar-token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired, handle refresh
        if ($this->client->isAccessTokenExpired()) {
            // Refresh the token if possible, else will need to get a new one
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
            }
        }

        $this->service = new Calendar($this->client);
    }

    /**
     * Create a Google Calendar event with Google Meet link
     *
     * @param string $summary Event title
     * @param string $description Event description
     * @param string $startDateTime Start date and time (ISO 8601 format)
     * @param string $endDateTime End date and time (ISO 8601 format)
     * @param string $timezone Timezone (e.g., 'America/New_York')
     * @param array $attendees Array of attendee emails
     * @param string $calendarId Calendar ID (default is 'primary')
     * @return array Event data including Meet link
     */
    public function createEvent(
        string $summary,
        string $description,
        string $startDateTime,
        string $endDateTime,
        string $timezone,
        array $attendees = [],
        string $calendarId = 'primary'
    ): array {
        try {
            $event = new Event([
                'summary' => $summary,
                'description' => $description,
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => $timezone,
                ],
                'end' => [
                    'dateTime' => $endDateTime,
                    'timeZone' => $timezone,
                ],
                'conferenceData' => [
                    'createRequest' => [
                        'requestId' => uniqid('meet-'),
                        'conferenceSolutionKey' => [
                            'type' => 'hangoutsMeet'
                        ],
                    ],
                ],
                'attendees' => array_map(function ($email) {
                    return ['email' => $email];
                }, $attendees),
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60], // 1 day before
                        ['method' => 'popup', 'minutes' => 60], // 1 hour before
                        ['method' => 'popup', 'minutes' => 10], // 10 minutes before
                    ],
                ],
                'guestsCanModify' => false,
                'guestsCanInviteOthers' => false,
                'guestsCanSeeOtherGuests' => true,
            ]);

            // Request conference data creation and send email notifications
            $optParams = [
                'conferenceDataVersion' => 1,
                'sendUpdates' => 'all', // Send email invitations to all attendees
            ];

            $createdEvent = $this->service->events->insert($calendarId, $event, $optParams);

            // Extract Meet link from the conference data
            $meetLink = null;
            if ($createdEvent->getConferenceData()) {
                $conferenceData = $createdEvent->getConferenceData();
                $entryPoints = $conferenceData->getEntryPoints();

                if ($entryPoints) {
                    foreach ($entryPoints as $entryPoint) {
                        if ($entryPoint->getEntryPointType() === 'video') {
                            $meetLink = $entryPoint->getUri();
                            break;
                        }
                    }
                }
            }

            return [
                'success' => true,
                'event_id' => $createdEvent->getId(),
                'meet_link' => $meetLink,
                'html_link' => $createdEvent->getHtmlLink(),
                'event_data' => $createdEvent,
            ];
        } catch (\Exception $e) {
            Log::error('Google Calendar event creation failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update an existing Google Calendar event
     *
     * @param string $eventId Event ID
     * @param array $updates Array of updates
     * @param string $calendarId Calendar ID
     * @return array
     */
    public function updateEvent(string $eventId, array $updates, string $calendarId = 'primary'): array
    {
        try {
            $event = $this->service->events->get($calendarId, $eventId);

            if (isset($updates['summary'])) {
                $event->setSummary($updates['summary']);
            }
            if (isset($updates['description'])) {
                $event->setDescription($updates['description']);
            }
            if (isset($updates['start'])) {
                $event->setStart(new EventDateTime($updates['start']));
            }
            if (isset($updates['end'])) {
                $event->setEnd(new EventDateTime($updates['end']));
            }

            $updatedEvent = $this->service->events->update($calendarId, $eventId, $event, [
                'sendUpdates' => 'all',
            ]);

            return [
                'success' => true,
                'event_data' => $updatedEvent,
            ];
        } catch (\Exception $e) {
            Log::error('Google Calendar event update failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a Google Calendar event
     *
     * @param string $eventId Event ID
     * @param string $calendarId Calendar ID
     * @return array
     */
    public function deleteEvent(string $eventId, string $calendarId = 'primary'): array
    {
        try {
            $this->service->events->delete($calendarId, $eventId, [
                'sendUpdates' => 'all',
            ]);

            return [
                'success' => true,
                'message' => 'Event deleted successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Google Calendar event deletion failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get Google OAuth authorization URL
     *
     * @return string
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle OAuth callback and store token
     *
     * @param string $authCode Authorization code from Google
     * @return bool
     */
    public function handleAuthCallback(string $authCode): bool
    {
        try {
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);

            if (array_key_exists('error', $accessToken)) {
                throw new \Exception(join(', ', $accessToken));
            }

            // Store the token to disk
            $tokenPath = storage_path('app/google-calendar-token.json');
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($accessToken));

            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar auth callback failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if the service is authenticated
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        $tokenPath = storage_path('app/google-calendar-token.json');

        if (!file_exists($tokenPath)) {
            return false;
        }

        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $this->client->setAccessToken($accessToken);

        return !$this->client->isAccessTokenExpired();
    }

    /**
     * Generate .ics file content for calendar import
     *
     * @param string $summary Event title
     * @param string $description Event description
     * @param string $startDateTime Start date and time
     * @param string $endDateTime End date and time
     * @param string $location Location or Meet link
     * @param string $organizerEmail Organizer email
     * @param string $attendeeEmail Attendee email
     * @return string .ics file content
     */
    public function generateIcsFile(
        string $summary,
        string $description,
        string $startDateTime,
        string $endDateTime,
        string $location,
        string $organizerEmail,
        string $attendeeEmail
    ): string {
        $start = new \DateTime($startDateTime);
        $end = new \DateTime($endDateTime);
        $now = new \DateTime();

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//P23 Africa//Business Consultation//EN\r\n";
        $ics .= "METHOD:REQUEST\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "DTSTART:" . $start->format('Ymd\THis\Z') . "\r\n";
        $ics .= "DTEND:" . $end->format('Ymd\THis\Z') . "\r\n";
        $ics .= "DTSTAMP:" . $now->format('Ymd\THis\Z') . "\r\n";
        $ics .= "UID:" . uniqid() . "@p23africa.com\r\n";
        $ics .= "CREATED:" . $now->format('Ymd\THis\Z') . "\r\n";
        $ics .= "DESCRIPTION:" . $this->escapeString($description) . "\r\n";
        $ics .= "LAST-MODIFIED:" . $now->format('Ymd\THis\Z') . "\r\n";
        $ics .= "LOCATION:" . $this->escapeString($location) . "\r\n";
        $ics .= "SEQUENCE:0\r\n";
        $ics .= "STATUS:CONFIRMED\r\n";
        $ics .= "SUMMARY:" . $this->escapeString($summary) . "\r\n";
        $ics .= "TRANSP:OPAQUE\r\n";
        $ics .= "ORGANIZER:mailto:" . $organizerEmail . "\r\n";
        $ics .= "ATTENDEE;CN=" . $attendeeEmail . ";RSVP=TRUE:mailto:" . $attendeeEmail . "\r\n";

        // Reminders
        $ics .= "BEGIN:VALARM\r\n";
        $ics .= "TRIGGER:-PT24H\r\n";
        $ics .= "ACTION:DISPLAY\r\n";
        $ics .= "DESCRIPTION:Reminder: Consultation tomorrow\r\n";
        $ics .= "END:VALARM\r\n";

        $ics .= "BEGIN:VALARM\r\n";
        $ics .= "TRIGGER:-PT1H\r\n";
        $ics .= "ACTION:DISPLAY\r\n";
        $ics .= "DESCRIPTION:Reminder: Consultation in 1 hour\r\n";
        $ics .= "END:VALARM\r\n";

        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }

    /**
     * Escape special characters for .ics format
     *
     * @param string $string
     * @return string
     */
    private function escapeString(string $string): string
    {
        return preg_replace('/([\,;])/', '\\\$1', $string);
    }
}
