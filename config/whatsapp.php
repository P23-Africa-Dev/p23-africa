<?php

return [
    // If null/empty, runtime will auto-enable when a valid account configuration exists.
    'enabled' => env('WHATSAPP_ENABLED'),

    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),

    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),

    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),

    'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),

    'webhook_secret' => env('WHATSAPP_WEBHOOK_SECRET'),

    'graph_version' => env('WHATSAPP_GRAPH_VERSION', 'v23.0'),

    'base_url' => env('WHATSAPP_BASE_URL', 'https://graph.facebook.com'),

    'queue' => (bool) env('WHATSAPP_QUEUE', true),

    'poll_interval' => (int) env('WHATSAPP_POLL_INTERVAL', 5),

    'status_poll_interval_ms' => (int) env('WHATSAPP_STATUS_POLL_INTERVAL_MS', 3000),

    'realtime_debug' => (bool) env('WHATSAPP_REALTIME_DEBUG', true),
];
