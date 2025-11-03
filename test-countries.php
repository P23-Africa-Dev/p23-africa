<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Helpers\ClickTracker;

echo "Testing country normalization...\n";

// Test common variations
$tests = [
    'USA' => 'United States',
    'US' => 'United States', 
    'America' => 'United States',
    'UK' => 'United Kingdom',
    'Britain' => 'United Kingdom',
    'England' => 'United Kingdom',
    'Canada' => 'Others',
    'Germany' => 'Others',
    'France' => 'Others',
    'Nigeria' => 'Nigeria',
    'Ghana' => 'Ghana'
];

foreach ($tests as $input => $expected) {
    $result = ClickTracker::normalizeCountry($input);
    $status = $result === $expected ? '✅' : '❌';
    echo "$status $input -> $result (expected: $expected)\n";
}