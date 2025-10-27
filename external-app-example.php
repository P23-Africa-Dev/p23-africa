<?php

/**
 * Integration example for external Laravel app at http://104.131.166.92
 * Place this in your external app's controller
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class BrnRegistrationController extends Controller
{
    // Replace with your actual P23-Africa domain
    private $API_BASE_URL = 'https://yourdomain.com';

    public function continueRegistration(Request $request)
    {
        $token = $request->get('token');

        if (!$token) {
            Log::error('BRN Registration: No token provided');
            return redirect('/error')->with('message', 'Invalid registration link - no token provided');
        }

        try {
            // First, validate the token without consuming it
            $validateResponse = Http::timeout(30)
                ->get($this->API_BASE_URL . '/api/brn/token/' . $token . '/validate');

            if (!$validateResponse->successful()) {
                Log::error('BRN Registration: Token validation failed', [
                    'token' => $token,
                    'status' => $validateResponse->status(),
                    'response' => $validateResponse->body()
                ]);
                return redirect('/error')->with('message', 'Invalid or expired registration link');
            }

            // Now get the actual data (this will mark token as used)
            $dataResponse = Http::timeout(30)
                ->get($this->API_BASE_URL . '/api/brn/token/' . $token);

            if (!$dataResponse->successful()) {
                Log::error('BRN Registration: Failed to get token data', [
                    'token' => $token,
                    'status' => $dataResponse->status(),
                    'response' => $dataResponse->body()
                ]);
                return redirect('/error')->with('message', 'Failed to retrieve registration data');
            }

            $apiData = $dataResponse->json();

            if (!$apiData['success']) {
                Log::error('BRN Registration: API returned error', [
                    'token' => $token,
                    'message' => $apiData['message']
                ]);
                return redirect('/error')->with('message', $apiData['message']);
            }

            // Decrypt the data using the SAME encryption key as P23-Africa
            // IMPORTANT: Both apps must have the same APP_KEY or shared encryption key
            $decryptedData = json_decode(Crypt::decryptString($apiData['data']), true);

            if (!$decryptedData) {
                Log::error('BRN Registration: Failed to decrypt data', ['token' => $token]);
                return redirect('/error')->with('message', 'Failed to decrypt registration data');
            }

            // Log successful data retrieval
            Log::info('BRN Registration: Successfully retrieved user data', [
                'token' => $token,
                'user_email' => $decryptedData['email'] ?? 'unknown'
            ]);

            // Now you have access to all the user data:
            // $decryptedData['name']
            // $decryptedData['email'] 
            // $decryptedData['company_name']
            // $decryptedData['phone']
            // $decryptedData['linkedin']
            // $decryptedData['country']
            // $decryptedData['position']
            // $decryptedData['years_of_operation']
            // $decryptedData['number_of_employees'] 
            // $decryptedData['selected_outcome']
            // $decryptedData['goals']

            // Pre-fill your registration form or create user directly
            return view('registration.continue', compact('decryptedData'));
        } catch (\Exception $e) {
            Log::error('BRN Registration: Exception occurred', [
                'token' => $token,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/error')->with('message', 'An error occurred processing your registration link');
        }
    }

    /**
     * Handle potential CORS issues by adding this method if needed
     */
    public function handleCors()
    {
        // If you experience CORS issues, you may need to handle preflight requests
        return response()->json(['status' => 'ok']);
    }
}
