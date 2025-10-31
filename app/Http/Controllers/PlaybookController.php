<?php

namespace App\Http\Controllers;

use App\Mail\PlaybookDownloadMail;
use App\Mail\SalesBookDownloadMail;
use App\Mail\UltimateFundingDownloadMail;
use App\Models\PlaybookDownload;
use App\Models\SalesBookDownload;
use App\Models\UltimateFundingDownload;
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
    public function playbookOne()
    {
        return view('playbook.funding-readiness');
    }
    public function playbookTwo()
    {
        return view('playbook.ultimate-funding');
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

    public function salesBookDownload(Request $request)
    {
        try {
            Log::info('Sales book download request received', $request->all());

            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:sales_book_downloads|max:255',
            ], [
                'name.required' => 'Full name is required.',
                'name.string' => 'Full name must be a valid text.',
                'name.max' => 'Full name cannot exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'Email address cannot exceed 255 characters.',
                'email.unique' => 'This email has already been used to download the guide.',
            ]);

            if ($validator->fails()) {
                Log::warning('Sales book download validation failed', [
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
            $existingDownload = SalesBookDownload::where('email', $request->email)->first();

            if ($existingDownload) {
                Log::info('Updating existing sales book download record', ['email' => $request->email]);
                // Update existing record
                $download = $existingDownload;
                $download->update([
                    'name' => $request->name,
                ]);
            } else {
                Log::info('Creating new sales book download record', ['email' => $request->email]);
                // Create new record
                $download = SalesBookDownload::create([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
            }

            // Send email with sales book attachment
            Log::info('Sending sales book email', ['email' => $request->email]);
            Mail::to($request->email)->send(new SalesBookDownloadMail($download));

            // Update email sent timestamp
            $download->update(['email_sent_at' => now()]);

            Log::info('Sales book download completed successfully', ['email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Funding Readiness Guide will be sent to your email shortly!'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database related errors
            Log::error('Database error in sales book download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred. Please try again later.'
            ], 500);
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // Email sending errors
            Log::error('Email transport error in sales book download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email could not be sent. Please check your email address and try again.'
            ], 500);
        } catch (\Exception $e) {
            // General errors
            Log::error('General error in sales book download', [
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

    public function ultimateFundingDownload(Request $request)
    {
        try {
            Log::info('Ultimate funding download request received', $request->all());

            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:ultimate_funding_downloads|max:255',
                'company' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'funding_stage' => 'required|string|max:255',
            ], [
                'name.required' => 'Full name is required.',
                'name.string' => 'Full name must be a valid text.',
                'name.max' => 'Full name cannot exceed 255 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'Email address cannot exceed 255 characters.',
                'email.unique' => 'This email has already been used to access the directory.',
                'company.required' => 'Company name is required.',
                'company.string' => 'Company name must be a valid text.',
                'company.max' => 'Company name cannot exceed 255 characters.',
                'country.required' => 'Country is required.',
                'country.string' => 'Country must be a valid text.',
                'country.max' => 'Country cannot exceed 255 characters.',
                'funding_stage.required' => 'Funding stage is required.',
                'funding_stage.string' => 'Funding stage must be a valid text.',
                'funding_stage.max' => 'Funding stage cannot exceed 255 characters.',
            ]);

            if ($validator->fails()) {
                Log::warning('Ultimate funding download validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Please check your form inputs.',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('Creating new ultimate funding download record', ['email' => $request->email]);

            // Create new record
            $download = UltimateFundingDownload::create([
                'name' => $request->name,
                'email' => $request->email,
                'company' => $request->company,
                'country' => $request->country,
                'funding_stage' => $request->funding_stage,
                'payment_completed' => false, // Will be updated after payment
                'amount_paid' => 50.00, // Set the expected amount
            ]);

            Log::info('Ultimate funding download record created successfully', ['id' => $download->id, 'email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Please proceed with payment to access the Ultimate Funding Directory.',
                'download_id' => $download->id,
                'amount' => 50.00,
                'user_email' => $download->email,
                'user_name' => $download->name
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database related errors
            Log::error('Database error in ultimate funding download', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred. Please try again later.'
            ], 500);
        } catch (\Exception $e) {
            // General errors
            Log::error('General error in ultimate funding download', [
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

    public function ultimateFundingPaymentCallback(Request $request)
    {
        try {
            Log::info('Ultimate funding payment callback received', $request->all());

            // Validate payment callback data
            $validator = Validator::make($request->all(), [
                'download_id' => 'required|integer|exists:ultimate_funding_downloads,id',
                'payment_reference' => 'required|string',
                'payment_status' => 'required|string',
                'amount' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                Log::warning('Ultimate funding payment callback validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment callback data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $download = UltimateFundingDownload::findOrFail($request->download_id);

            // Update payment details
            $download->update([
                'payment_completed' => $request->payment_status === 'successful',
                'payment_reference' => $request->payment_reference,
                'payment_status' => $request->payment_status,
                'payment_date' => now(),
                'payment_data' => $request->all()
            ]);

            // Send email if payment was successful
            if ($request->payment_status === 'successful') {
                Log::info('Sending ultimate funding directory email', ['email' => $download->email]);
                Mail::to($download->email)->send(new UltimateFundingDownloadMail($download));

                // Update email sent timestamp
                $download->update(['email_sent_at' => now()]);

                Log::info('Ultimate funding directory email sent successfully', ['email' => $download->email]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful! The Ultimate Funding Directory has been sent to your email.'
                ]);
            } else {
                Log::warning('Payment failed for ultimate funding download', [
                    'download_id' => $download->id,
                    'payment_status' => $request->payment_status
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment was not successful. Please try again.'
                ], 422);
            }
        } catch (\Exception $e) {
            Log::error('Error in ultimate funding payment callback', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred processing the payment. Please contact support.'
            ], 500);
        }
    }

    public function dashboard()
    {
        try {
            // Get all statistics
            $playbookDownloads = PlaybookDownload::count();
            $salesBookDownloads = SalesBookDownload::count();
            $ultimateFundingDownloads = UltimateFundingDownload::count();
            $ultimateFundingPaid = UltimateFundingDownload::where('payment_completed', true)->count();
            $totalRevenue = UltimateFundingDownload::where('payment_completed', true)->sum('amount_paid');

            // Recent downloads
            $recentPlaybookDownloads = PlaybookDownload::latest()->take(5)->get();
            $recentSalesBookDownloads = SalesBookDownload::latest()->take(5)->get();
            $recentUltimateFundingDownloads = UltimateFundingDownload::latest()->take(5)->get();
            
            $stats = [
                'playbook_downloads' => $playbookDownloads,
                'sales_book_downloads' => $salesBookDownloads,
                'ultimate_funding_downloads' => $ultimateFundingDownloads,
                'ultimate_funding_paid' => $ultimateFundingPaid,
                'total_revenue' => $totalRevenue,
                'total_downloads' => $playbookDownloads + $salesBookDownloads + $ultimateFundingDownloads,
            ];

            $recentDownloads = [
                'playbook' => $recentPlaybookDownloads,
                'sales_book' => $recentSalesBookDownloads,
                'ultimate_funding' => $recentUltimateFundingDownloads,
            ];

            return view('admin.playbooks.dashboard', compact('stats', 'recentDownloads'));
        } catch (\Exception $e) {
            Log::error('Error loading playbook dashboard', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'An error occurred loading the dashboard.');
        }
    }
}
