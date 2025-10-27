<?php

/**
 * Test script for BRN Token API
 * Place this file on your external server at http://104.131.166.92
 * and access it via browser to test the API connection
 */

// Configuration
$API_BASE_URL = 'https://yourdomain.com'; // Replace with your actual P23-Africa domain
$TEST_TOKEN = 'test-token-123'; // Use a real token from your database

echo "<h1>BRN Token API Test</h1>";

// Test 1: Validate Token (doesn't consume it)
echo "<h2>Test 1: Validate Token</h2>";
$validateUrl = $API_BASE_URL . '/api/brn/token/' . $TEST_TOKEN . '/validate';
echo "Testing URL: " . $validateUrl . "<br>";

$response = @file_get_contents($validateUrl, false, stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Accept: application/json',
        'timeout' => 30
    ]
]));

if ($response === false) {
    echo "<span style='color: red;'>❌ Failed to connect to API</span><br>";
    echo "Error: " . error_get_last()['message'] . "<br>";
} else {
    echo "<span style='color: green;'>✅ API Connection Successful</span><br>";
    echo "Response: <pre>" . $response . "</pre>";
}

echo "<hr>";

// Test 2: Get Token Data (consumes the token)
echo "<h2>Test 2: Get Token Data</h2>";
$getDataUrl = $API_BASE_URL . '/api/brn/token/' . $TEST_TOKEN;
echo "Testing URL: " . $getDataUrl . "<br>";

$response = @file_get_contents($getDataUrl, false, stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Accept: application/json',
        'timeout' => 30
    ]
]));

if ($response === false) {
    echo "<span style='color: red;'>❌ Failed to connect to API</span><br>";
    echo "Error: " . error_get_last()['message'] . "<br>";
} else {
    echo "<span style='color: green;'>✅ API Connection Successful</span><br>";

    $data = json_decode($response, true);
    if ($data && $data['success']) {
        echo "Success! Got encrypted data:<br>";
        echo "Encrypted Data: " . substr($data['data'], 0, 50) . "...<br>";

        // If you have the same encryption key, you can decrypt:
        // $decrypted = json_decode(Crypt::decryptString($data['data']), true);
        // print_r($decrypted);
    } else {
        echo "API Response: <pre>" . $response . "</pre>";
    }
}

echo "<hr>";
echo "<h3>Instructions:</h3>";
echo "<ol>";
echo "<li>Replace 'yourdomain.com' with your actual P23-Africa domain</li>";
echo "<li>Replace 'test-token-123' with a real token from your BRN database</li>";
echo "<li>Ensure your P23-Africa app is accessible from the internet</li>";
echo "<li>Make sure API routes are enabled (which they now are!)</li>";
echo "</ol>";
