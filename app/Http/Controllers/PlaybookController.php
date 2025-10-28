<?php

namespace App\Http\Controllers;

use App\Mail\PlaybookDownloadMail;
use App\Models\PlaybookDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PlaybookController extends Controller
{
    public function landing()
    {
        return view('playbook-landing');
    }

    public function download(Request $request)
    {
        try {
            Log::info('Playbook download request received', $request->all());

            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:playbook_downloads|max:255',
                'company' => 'required|string|max:255',
            ], [
                'name.required' => 'Full name is required.',
                'name.string' => 'Full name must be a valid text.',
                'name.max' => 'Full name cannot exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'Email address cannot exceed 255 characters.',
                'company.required' => 'Company name is required.',
                'company.string' => 'Company name must be a valid text.',
                'company.max' => 'Company name cannot exceed 255 characters.',
            ]);

            if ($validator->fails()) {
                Log::warning('Playbook download validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Please check your form inputs.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if email already exists (optional - remove if you want to allow multiple downloads)
            $existingDownload = PlaybookDownload::where('email', $request->email)->first();

            if ($existingDownload) {
                Log::info('Updating existing playbook download record', ['email' => $request->email]);
                // Update existing record
                $download = $existingDownload;
                $download->update([
                    'name' => $request->name,
                    'company' => $request->company,
                ]);
            } else {
                Log::info('Creating new playbook download record', ['email' => $request->email]);
                // Create new record
                $download = PlaybookDownload::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'company' => $request->company,
                ]);
            }

            // Send email with playbook attachment
            Log::info('Sending playbook email', ['email' => $request->email]);
            Mail::to($request->email)->send(new PlaybookDownloadMail($download));

            // Update email sent timestamp
            $download->update(['email_sent_at' => now()]);

            Log::info('Playbook download completed successfully', ['email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Playbook will be sent to your email shortly!'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database related errors
            Log::error('Database error in playbook download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred. Please try again later.'
            ], 500);
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // Email sending errors
            Log::error('Email transport error in playbook download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email could not be sent. Please check your email address and try again.'
            ], 500);
        } catch (\Exception $e) {
            // General errors
            Log::error('General error in playbook download', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }
}
