# Google Calendar Integration Setup Guide

## Prerequisites

1. Google Cloud Project with Calendar API enabled
2. OAuth 2.0 credentials (Client ID and Client Secret)

## Setup Steps

### 1. Create Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google Calendar API:
    - Go to "APIs & Services" > "Library"
    - Search for "Google Calendar API"
    - Click "Enable"

### 2. Create OAuth 2.0 Credentials

1. Go to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "OAuth client ID"
3. Select "Web application"
4. Add authorized redirect URIs:
    ```
    http://localhost/google/calendar/callback
    https://yourdomain.com/google/calendar/callback
    ```
5. Download the JSON credentials file

### 3. Configure Laravel Application

#### A. Add credentials file

1. Rename the downloaded JSON file to `google-calendar-credentials.json`
2. Place it in `storage/app/google-calendar-credentials.json`

#### B. Update `.env` file

Add the following configuration:

```env
# Admin email for consultation bookings
ADMIN_EMAIL=admin@p23africa.com

# Google Calendar Configuration
GOOGLE_CALENDAR_CLIENT_ID=your_client_id_here
GOOGLE_CALENDAR_CLIENT_SECRET=your_client_secret_here
GOOGLE_CALENDAR_REDIRECT_URI=http://localhost/google/calendar/callback
```

### 4. Authorize Google Calendar Access

#### Option 1: Via Admin Dashboard (Recommended)

1. Log in to the admin dashboard
2. Navigate to Settings > Integrations
3. Click "Connect Google Calendar"
4. Follow the OAuth flow to authorize access

#### Option 2: Via Direct URL

1. Visit: `https://yourdomain.com/google/calendar/auth`
2. Sign in with your Google account
3. Grant the requested permissions
4. You'll be redirected back to your application

### 5. Verify Installation

After authorization, a token file will be created at:

```
storage/app/google-calendar-token.json
```

## Features Included

### For Clients

-   ✅ Automatic Google Calendar event creation
-   ✅ Google Meet link generation
-   ✅ Email invitation with calendar attachment
-   ✅ .ics file download for manual import
-   ✅ Automatic reminders (1 day, 1 hour, 10 minutes before)

### For Admin

-   ✅ Event automatically added to admin calendar
-   ✅ Client information included in event description
-   ✅ Google Meet link for easy access
-   ✅ Email notification with meeting details

## Usage Flow

1. **Client completes consultation form and payment**
2. **Client selects date/time on scheduling page**
3. **System automatically:**
    - Creates Google Calendar event
    - Generates Google Meet link
    - Adds event to both admin and client calendars
    - Sends email invitations to both parties
    - Provides .ics download link

## Troubleshooting

### Token Expired Error

If you see token expired errors:

1. Delete `storage/app/google-calendar-token.json`
2. Re-authorize via `/google/calendar/auth`

### Permission Denied

Ensure the storage directory is writable:

```bash
chmod -R 775 storage/
```

### Meet Link Not Generated

Verify that:

1. Google Calendar API is enabled
2. OAuth scope includes `https://www.googleapis.com/auth/calendar`
3. Conference data version is set to 1 in the request

## Security Notes

⚠️ **Important Security Considerations:**

1. Never commit credential files to version control
2. Add to `.gitignore`:
    ```
    storage/app/google-calendar-credentials.json
    storage/app/google-calendar-token.json
    ```
3. Use environment-specific redirect URIs
4. Regularly rotate OAuth credentials
5. Monitor API usage in Google Cloud Console

## API Limits

-   Google Calendar API quota: 1,000,000 queries per day
-   Rate limit: 10 requests per second per user

## Support

For issues or questions, contact the development team.
