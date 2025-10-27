<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BrnRegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrnTokenController extends Controller
{
    /**
     * Retrieve encrypted data using a token
     * 
     * @param string $token
     * @return JsonResponse
     */
    public function getTokenData(string $token): JsonResponse
    {
        try {
            // Find the token record
            $record = BrnRegistrationToken::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired token'
                ], 404);
            }

            // Token remains reusable until expiration (no marking as used)

            return response()->json([
                'success' => true,
                'data' => $record->encrypted_data,
                'message' => 'Token data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the token'
            ], 500);
        }
    }

    /**
     * Get token information without marking as used (for validation)
     * 
     * @param string $token
     * @return JsonResponse
     */
    public function validateToken(string $token): JsonResponse
    {
        try {
            $record = BrnRegistrationToken::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired token',
                    'valid' => false
                ], 404);
            }

            return response()->json([
                'success' => true,
                'valid' => true,
                'expires_at' => $record->expires_at->toISOString(),
                'message' => 'Token is valid'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while validating the token',
                'valid' => false
            ], 500);
        }
    }
}
