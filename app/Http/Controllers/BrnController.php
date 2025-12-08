<?php

namespace App\Http\Controllers;

use App\Helpers\ClickTracker;
use App\Mail\BrnDeclined;
use App\Models\BrnSubmission;
use App\Models\Event;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BrnController extends Controller
{
    public function brnIndex()
    {
        $events = Event::whereIn('display', ['open_to_all', 'brn_only'])
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->take(2) // or ->limit(5)
            ->get();

        ClickTracker::track('NOEL_Page');

        return view('brn', compact('events'));
    }

    public function showForm()
    {
        ClickTracker::track('NOEL_Form_Page');

        return view('brn-form');
    }

    public function checkEmail(Request $request)
    {
        // Add rate limiting - generous limit for UX
        if (RateLimiter::tooManyAttempts('check-email:' . $request->ip(), 30)) {
            return response()->json([
                'exists' => false,
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }

        RateLimiter::hit('check-email:' . $request->ip(), 60);

        try {
            $email = $request->input('email');

            // Basic validation
            if (empty($email)) {
                return response()->json([
                    'exists' => false,
                    'message' => 'Email is required.'
                ], 400);
            }

            // Clean and normalize email
            $email = strtolower(trim($email));
            $email = preg_replace('/[\x00-\x1F\x7F]/u', '', $email);

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'exists' => false,
                    'valid' => false,
                    'message' => 'Please enter a valid email address.'
                ], 422);
            }

            // Check for existing email (case-insensitive)
            $exists = BrnSubmission::whereRaw('LOWER(email) = ?', [$email])->exists();

            if ($exists) {
                return response()->json([
                    'exists' => true,
                    'valid' => true,
                    'message' => 'This email address is already registered.'
                ], 422);
            }

            return response()->json([
                'exists' => false,
                'valid' => true,
                'message' => 'Email is available.'
            ]);
        } catch (\Exception $e) {
            Log::warning('Email check error', ['error' => $e->getMessage()]);

            return response()->json([
                'exists' => false,
                'valid' => true,
                'message' => 'Email check completed.'
            ]);
        }
    }

    public function store(Request $request)
    {
        // Add rate limiting - more generous limit
        if (RateLimiter::tooManyAttempts('submit-brn:' . $request->ip(), 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many submissions. Please try again in a few minutes.'
            ], 429);
        }

        RateLimiter::hit('submit-brn:' . $request->ip(), 300); // 5 minutes

        try {
            // Pre-process and sanitize input data BEFORE validation
            $sanitizedData = $this->sanitizeInputData($request->all());

            // Merge sanitized data back into request for validation
            $request->merge($sanitizedData);

            // Validate with relaxed but comprehensive rules
            $validated = $request->validate([
                // Personal Information
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email:rfc,dns|max:255',
                'phone' => 'required|string|min:6|max:50',
                'country_code' => 'nullable|string|max:10',
                'position' => 'required|string|min:2|max:255',

                // Company Information
                'company_name' => 'required|string|min:2|max:255',
                'reg_number' => 'nullable|string|max:255',
                'website' => 'nullable|string|max:500',
                'year_established' => 'nullable|string|max:10',
                'linkedin' => 'nullable|string|max:500',
                'country' => 'required|string|max:100',
                'industry' => 'nullable|string|max:100',
                'categories' => 'nullable|string|max:1000',

                // Business Details
                'number_of_employees' => 'required|string|max:50',
                'years_of_operation' => 'required|string|max:50',

                // Goals and Interests
                'offer_value' => 'nullable|string|max:2000',
                'goals' => 'required|string|min:10|max:5000',
                'selected_outcome' => 'required|string|max:500',
                'b2b_interest' => 'required|string|max:20',
            ], $this->getValidationMessages());

            // Check for duplicate email after validation (with case-insensitive check)
            $emailExists = BrnSubmission::whereRaw('LOWER(email) = ?', [strtolower($validated['email'])])->exists();
            if ($emailExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email address is already registered. If you need to update your application, please contact us.',
                    'errors' => ['email' => ['This email address is already registered.']]
                ], 422);
            }

            // Post-process validated data
            $processedData = $this->processValidatedData($validated, $request);

            // Create the submission
            $submission = BrnSubmission::create($processedData);

            // Send notification to admins (wrapped in try-catch to not fail submission)
            try {
                AdminNotificationService::notifyNewBrnApplication($submission);
            } catch (\Exception $e) {
                Log::warning('Failed to send BRN notification', ['error' => $e->getMessage()]);
            }

            // Track submission for analytics
            try {
                ClickTracker::track('BRN_Form_Submission');
            } catch (\Exception $e) {
                // Don't fail submission if tracking fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Your application was submitted successfully. You will be contacted soon.',
                'submission_id' => $submission->id,
                'clear_storage' => true
            ]);
        } catch (ValidationException $e) {
            // Return user-friendly validation errors
            return response()->json([
                'success' => false,
                'message' => 'Please check the form and correct any errors.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('BRN Form Submission Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application. Please try again or contact support if the problem persists.'
            ], 500);
        }
    }

    /**
     * Sanitize and clean input data before validation
     */
    private function sanitizeInputData(array $data): array
    {
        // Trim all string values
        $data = array_map(function ($value) {
            return is_string($value) ? trim($value) : $value;
        }, $data);

        // Clean and format name
        if (!empty($data['name'])) {
            $data['name'] = $this->cleanName($data['name']);
        }

        // Clean and lowercase email
        if (!empty($data['email'])) {
            $data['email'] = $this->cleanEmail($data['email']);
        }

        // Clean phone number
        if (!empty($data['phone'])) {
            $data['phone'] = $this->cleanPhoneNumber($data['phone']);
        }

        // Clean country code
        if (!empty($data['country_code'])) {
            $data['country_code'] = preg_replace('/[^0-9+]/', '', $data['country_code']);
        }

        // Normalize website URL before validation
        if (!empty($data['website'])) {
            $data['website'] = $this->normalizeWebsiteURL($data['website']);
        }

        // Normalize LinkedIn URL before validation
        if (!empty($data['linkedin'])) {
            $data['linkedin'] = $this->normalizeLinkedInURL($data['linkedin']);
        }

        // Normalize year_established
        if (!empty($data['year_established'])) {
            $data['year_established'] = $this->normalizeYear($data['year_established']);
        }

        // Normalize number_of_employees to expected format
        if (!empty($data['number_of_employees'])) {
            $data['number_of_employees'] = $this->normalizeEmployeeCount($data['number_of_employees']);
        }

        // Normalize years_of_operation to expected format
        if (!empty($data['years_of_operation'])) {
            $data['years_of_operation'] = $this->normalizeYearsOfOperation($data['years_of_operation']);
        }

        // Normalize b2b_interest
        if (!empty($data['b2b_interest'])) {
            $data['b2b_interest'] = $this->normalizeB2bInterest($data['b2b_interest']);
        }

        // Clean company name
        if (!empty($data['company_name'])) {
            $data['company_name'] = $this->cleanCompanyName($data['company_name']);
        }

        // Clean position/title
        if (!empty($data['position'])) {
            $data['position'] = Str::of($data['position'])->trim()->title()->toString();
        }

        return $data;
    }

    /**
     * Process validated data after validation
     */
    private function processValidatedData(array $validated, Request $request): array
    {
        // Map 'name' to 'full_name' to match database column
        if (isset($validated['name'])) {
            $validated['full_name'] = $validated['name'];
            unset($validated['name']);
        }

        // Combine country code and phone if provided
        if (!empty($request->country_code) && !empty($validated['phone'])) {
            $countryCode = preg_replace('/[^0-9+]/', '', $request->country_code);
            $phone = preg_replace('/^0+/', '', $validated['phone']); // Remove leading zeros
            $validated['phone'] = $countryCode . $phone;
        }

        // Remove country_code from validated data as we don't store it separately
        unset($validated['country_code']);

        // Set default status
        $validated['status'] = 'pending';

        return $validated;
    }

    /**
     * Get user-friendly validation messages
     */
    private function getValidationMessages(): array
    {
        return [
            // Name
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name is too long.',

            // Email
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address is too long.',

            // Phone
            'phone.required' => 'Please enter your phone number.',
            'phone.min' => 'Phone number is too short.',
            'phone.max' => 'Phone number is too long.',

            // Position
            'position.required' => 'Please enter your job title or position.',
            'position.min' => 'Position must be at least 2 characters.',

            // Company
            'company_name.required' => 'Please enter your company name.',
            'company_name.min' => 'Company name must be at least 2 characters.',

            // Country
            'country.required' => 'Please select your country.',

            // Business Details
            'number_of_employees.required' => 'Please select the number of employees.',
            'years_of_operation.required' => 'Please select years of operation.',

            // Goals
            'goals.required' => 'Please describe your company and goals.',
            'goals.min' => 'Please provide a bit more detail about your company (at least 10 characters).',
            'goals.max' => 'Description is too long.',

            // Selected Outcome
            'selected_outcome.required' => 'Please select what interests you most from this network.',

            // B2B Interest
            'b2b_interest.required' => 'Please indicate your interest in B2B opportunities.',

            // Optional fields
            'website.max' => 'Website URL is too long.',
            'linkedin.max' => 'LinkedIn URL is too long.',
            'offer_value.max' => 'Offer description is too long.',
            'categories.max' => 'Categories text is too long.',
        ];
    }

    /**
     * Clean and format name
     */
    private function cleanName(string $name): string
    {
        // Remove extra whitespace
        $name = preg_replace('/\s+/', ' ', trim($name));

        // Convert to title case
        $name = Str::of($name)->title()->toString();

        // Handle special cases like McDonald, O'Brien, etc.
        $name = preg_replace_callback('/\b(Mc|Mac|O\')(\w)/i', function ($matches) {
            return $matches[1] . strtoupper($matches[2]);
        }, $name);

        return $name;
    }

    /**
     * Clean and format email
     */
    private function cleanEmail(string $email): string
    {
        // Remove whitespace and convert to lowercase
        $email = strtolower(trim($email));

        // Remove any invisible characters
        $email = preg_replace('/[\x00-\x1F\x7F]/u', '', $email);

        return $email;
    }

    /**
     * Clean phone number
     */
    private function cleanPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except + at the beginning
        $phone = trim($phone);

        // Keep only digits and the leading +
        if (Str::startsWith($phone, '+')) {
            $phone = '+' . preg_replace('/[^0-9]/', '', substr($phone, 1));
        } else {
            $phone = preg_replace('/[^0-9]/', '', $phone);
        }

        return $phone;
    }

    /**
     * Clean company name
     */
    private function cleanCompanyName(string $name): string
    {
        // Remove extra whitespace
        $name = preg_replace('/\s+/', ' ', trim($name));

        // Don't force title case on company names as they may have specific capitalization
        // Just clean up the whitespace

        return $name;
    }

    /**
     * Normalize year value
     */
    private function normalizeYear(?string $year): ?string
    {
        if (empty($year)) return null;

        // Extract just the 4-digit year
        preg_match('/\d{4}/', $year, $matches);

        if (!empty($matches[0])) {
            $yearInt = (int) $matches[0];
            // Validate year range
            if ($yearInt >= 1900 && $yearInt <= (date('Y') + 1)) {
                return (string) $yearInt;
            }
        }

        return null;
    }

    /**
     * Normalize employee count to expected format
     */
    private function normalizeEmployeeCount(string $value): string
    {
        $value = strtolower(trim($value));

        // Map various inputs to standard format
        $mappings = [
            '1-10' => '1-10',
            '1 - 10' => '1-10',
            '1 to 10' => '1-10',
            '10 or less' => '1-10',
            'under 10' => '1-10',
            '11-50' => '11-50',
            '11 - 50' => '11-50',
            '11 to 50' => '11-50',
            '50-200' => '50-200',
            '50 - 200' => '50-200',
            '50 to 200' => '50-200',
            '51-200' => '50-200',
            '200+' => '200+',
            '200 +' => '200+',
            'over 200' => '200+',
            'more than 200' => '200+',
            '201+' => '200+',
        ];

        return $mappings[$value] ?? $value;
    }

    /**
     * Normalize years of operation to expected format
     */
    private function normalizeYearsOfOperation(string $value): string
    {
        $value = strtolower(trim($value));

        // Map various inputs to standard format
        $mappings = [
            '0-5' => '0-5',
            '0 - 5' => '0-5',
            '0 to 5' => '0-5',
            'under 5' => '0-5',
            'less than 5' => '0-5',
            '1-5' => '0-5',
            '6-10' => '6-10',
            '6 - 10' => '6-10',
            '6 to 10' => '6-10',
            '5-10' => '6-10',
            '11-above' => '11-Above',
            '11-Above' => '11-Above',
            '11 - above' => '11-Above',
            '11 and above' => '11-Above',
            'over 10' => '11-Above',
            'more than 10' => '11-Above',
            '10+' => '11-Above',
            '11+' => '11-Above',
        ];

        return $mappings[$value] ?? $value;
    }

    /**
     * Normalize B2B interest value
     */
    private function normalizeB2bInterest(string $value): string
    {
        $value = strtolower(trim($value));

        $mappings = [
            'yes' => 'yes',
            'y' => 'yes',
            'true' => 'yes',
            '1' => 'yes',
            'no' => 'no',
            'n' => 'no',
            'false' => 'no',
            '0' => 'no',
            'maybe' => 'maybe',
            'perhaps' => 'maybe',
            'not sure' => 'maybe',
            'unsure' => 'maybe',
        ];

        return $mappings[$value] ?? $value;
    }

    /**
     * Normalize LinkedIn URL to standard format
     * Accepts various formats: full URL, username, profile path
     */
    private function normalizeLinkedInURL($url)
    {
        if (empty($url)) return null;

        // Remove any leading/trailing whitespace
        $url = trim($url);

        // If it's empty after trimming, return null
        if (empty($url)) return null;

        // Remove any invisible characters
        $url = preg_replace('/[\x00-\x1F\x7F]/u', '', $url);

        // Check if it already looks like a valid LinkedIn URL
        if (preg_match('/^https?:\/\/(www\.)?linkedin\.com\//i', $url)) {
            return $url;
        }

        // Add https:// if starts with linkedin.com
        if (preg_match('/^(www\.)?linkedin\.com\//i', $url)) {
            return 'https://' . $url;
        }

        // If it contains linkedin.com somewhere, try to extract and fix the URL
        if (stripos($url, 'linkedin.com') !== false) {
            // Remove any protocol issues
            $url = preg_replace('/^.*?(linkedin\.com)/i', 'https://www.$1', $url);
            return $url;
        }

        // Assume it's a username/profile identifier
        // Clean up the username - remove @ symbols and slashes
        $username = trim($url, '/@');
        $username = preg_replace('/[^a-zA-Z0-9\-_]/', '', $username);

        if (!empty($username)) {
            return 'https://www.linkedin.com/in/' . $username;
        }

        return null;
    }

    /**
     * Normalize website URL to standard format
     * Handles various input formats gracefully
     */
    private function normalizeWebsiteURL($url)
    {
        if (empty($url)) return null;

        // Remove any leading/trailing whitespace
        $url = trim($url);

        // If it's empty after trimming, return null
        if (empty($url)) return null;

        // Remove any invisible characters
        $url = preg_replace('/[\x00-\x1F\x7F]/u', '', $url);

        // Check if it already has a protocol
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        // Check if it starts with // (protocol-relative)
        if (Str::startsWith($url, '//')) {
            return 'https:' . $url;
        }

        // Check if it looks like a domain (has a dot and no spaces)
        if (strpos($url, '.') !== false && strpos($url, ' ') === false) {
            // Remove any accidental leading slashes
            $url = ltrim($url, '/');
            return 'https://' . $url;
        }

        // If it doesn't look like a URL at all, return null to avoid storing garbage
        return null;
    }

    /**
     * Display success page after form submission
     */
    public function success()
    {
        return view('brn-success');
    }
}
