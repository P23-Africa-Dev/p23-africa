<?php

use Illuminate\Support\Facades\Route;
use App\Models\ConsultationApplication;

Route::get('/test-admin-debug', function () {
    $applications = ConsultationApplication::with(['payments', 'scheduledSessions'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    $output = "Applications found: " . $applications->count() . "\n\n";
    
    foreach ($applications as $app) {
        $output .= "ID: {$app->id}\n";
        $output .= "Name: " . ($app->name ?? 'NULL') . "\n";
        $output .= "Email: " . ($app->email ?? 'NULL') . "\n";
        $output .= "Status: {$app->status}\n";
        $output .= "Is Draft: " . ($app->isDraft() ? 'YES' : 'NO') . "\n";
        $output .= "Created: {$app->created_at}\n";
        $output .= "Session ID: " . ($app->session_id ?? 'NULL') . "\n";
        $output .= "---\n";
    }
    
    return response($output, 200, ['Content-Type' => 'text/plain']);
});
