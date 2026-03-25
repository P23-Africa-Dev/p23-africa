<?php

use App\Http\Controllers\Api\BrnTokenController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Event API routes
Route::get('/event/{id}/booking-fields', [EventController::class, 'getBookingFields'])
    ->name('api.event.booking-fields');

// Chat API routes for consultation help system
Route::prefix('consultation')->group(function () {
    Route::post('/chat/send-message', [App\Http\Controllers\ConsultationController::class, 'sendChatMessage'])
        ->name('api.consultation.chat.send');

    Route::get('/chat/{applicationId}/messages', [App\Http\Controllers\ConsultationController::class, 'getChatMessages'])
        ->name('api.consultation.chat.messages');
});

// BRN Token API routes - accessible without authentication for external app
Route::prefix('brn')->group(function () {
    // Get encrypted data using token (marks token as used)
    Route::get('/token/{token}', [BrnTokenController::class, 'getTokenData'])
        ->name('api.brn.token.data');

    // Validate token without consuming it
    Route::get('/token/{token}/validate', [BrnTokenController::class, 'validateToken'])
        ->name('api.brn.token.validate');
});

// Lead Email Webhook - for receiving email replies from external email providers
Route::post('/webhooks/lead-email/inbound', [App\Http\Controllers\Api\LeadEmailWebhookController::class, 'handleInbound'])
    ->name('api.webhooks.lead-email.inbound');
