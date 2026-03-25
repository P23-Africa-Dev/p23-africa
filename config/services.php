<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'currency' => env('STRIPE_CURRENCY', 'GBP'),
        'amount' => env('STRIPE_AMOUNT', 75),
        'allowed_currencies' => env('STRIPE_ALLOWED_CURRENCIES', 'GBP,USD,EUR,NGN,GHS,KES,ZAR,CAD,AUD'),
        'fx_rate_url' => env('STRIPE_FX_RATE_URL', 'https://api.exchangerate.host/convert'),
        'fx_fallback_rates' => [
            'GBP_NGN' => (float) env('STRIPE_FX_FALLBACK_GBP_NGN', 2000),
            'GBP_GHS' => (float) env('STRIPE_FX_FALLBACK_GBP_GHS', 16),
            'GBP_KES' => (float) env('STRIPE_FX_FALLBACK_GBP_KES', 165),
            'GBP_ZAR' => (float) env('STRIPE_FX_FALLBACK_GBP_ZAR', 24),
            'GBP_USD' => (float) env('STRIPE_FX_FALLBACK_GBP_USD', 1.3),
            'GBP_EUR' => (float) env('STRIPE_FX_FALLBACK_GBP_EUR', 1.17),
            'GBP_CAD' => (float) env('STRIPE_FX_FALLBACK_GBP_CAD', 1.75),
            'GBP_AUD' => (float) env('STRIPE_FX_FALLBACK_GBP_AUD', 2.0),
        ],
    ],

    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
        'currency' => env('FLUTTERWAVE_CURRENCY', 'USD'),
        'country' => env('FLUTTERWAVE_COUNTRY', 'NG'),
        'amount' => env('FLUTTERWAVE_AMOUNT', 100),
        'payment_options' => env('FLUTTERWAVE_PAYMENT_OPTIONS', 'card'),
    ],

    'admin' => [
        'email' => env('ADMIN_EMAIL', 'contact@p23africa.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Workspace Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Workspace email integration.
    | This allows admin users to send emails using their own @yourdomain.com
    | email addresses through Google Workspace SMTP.
    |
    */
    'google_workspace' => [
        // Allowed email domains for sending (e.g., ['p23africa.com', 'yourdomain.com'])
        // Leave empty to allow all domains
        'allowed_domains' => array_filter(explode(',', env('GOOGLE_WORKSPACE_ALLOWED_DOMAINS', ''))),
    ],

];
