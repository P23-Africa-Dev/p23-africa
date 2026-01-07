<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LeadEmailWebhookController extends Controller
{
    /**
     * Handle incoming email webhook (e.g., from SendGrid, Mailgun, Postmark, etc.)
     * 
     * This endpoint receives inbound email notifications when a lead replies
     * to an email sent from the CRM.
     */
    public function handleInbound(Request $request)
    {
        try {
            Log::info('Incoming email webhook received', [
                'headers' => $request->headers->all(),
                'body' => $request->all()
            ]);

            // Extract email data from the webhook payload
            // Note: The exact format depends on your email provider (SendGrid, Mailgun, etc.)
            $emailData = $this->parseInboundEmail($request);

            if (!$emailData) {
                return response()->json(['error' => 'Invalid email data'], 400);
            }

            // Find the original email thread by In-Reply-To header or References
            $originalEmail = $this->findOriginalEmail($emailData);

            if (!$originalEmail) {
                Log::warning('Could not find original email for reply', $emailData);
                return response()->json(['error' => 'Original email not found'], 404);
            }

            // Get the lead associated with this email
            $lead = $originalEmail->lead;

            if (!$lead) {
                Log::warning('Lead not found for email reply', ['email_id' => $originalEmail->id]);
                return response()->json(['error' => 'Lead not found'], 404);
            }

            // Create the incoming email record
            $incomingEmail = LeadEmail::create([
                'lead_id' => $lead->id,
                'user_id' => $originalEmail->user_id, // Associate with the admin who sent the original
                'from_email' => $emailData['from_email'],
                'from_name' => $emailData['from_name'] ?? $lead->full_name,
                'to_email' => $emailData['to_email'],
                'to_name' => $emailData['to_name'],
                'subject' => $emailData['subject'],
                'body' => $emailData['body'],
                'body_html' => $emailData['body_html'] ?? null,
                'direction' => 'incoming',
                'message_id' => $emailData['message_id'] ?? Str::uuid()->toString() . '@inbound',
                'in_reply_to' => $emailData['in_reply_to'] ?? $originalEmail->message_id,
                'thread_id' => $originalEmail->thread_id,
                'status' => 'received',
                'metadata' => $emailData['metadata'] ?? null,
            ]);

            // Log activity on the lead
            $lead->logActivity(
                'email',
                'Email reply received: ' . $emailData['subject'],
                'Reply received from ' . $emailData['from_email'],
                ['email_id' => $incomingEmail->id]
            );

            Log::info('Inbound email processed successfully', [
                'lead_id' => $lead->id,
                'email_id' => $incomingEmail->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email received and processed',
                'email_id' => $incomingEmail->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to process inbound email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to process email'
            ], 500);
        }
    }

    /**
     * Parse the inbound email from the webhook payload
     * 
     * This method should be customized based on your email provider's webhook format.
     * Common providers: SendGrid, Mailgun, Postmark, AWS SES
     */
    protected function parseInboundEmail(Request $request): ?array
    {
        // Generic parsing - customize based on your email provider

        // Check for SendGrid format
        if ($request->has('from') && $request->has('text')) {
            return $this->parseSendGridFormat($request);
        }

        // Check for Mailgun format
        if ($request->has('sender') && $request->has('body-plain')) {
            return $this->parseMailgunFormat($request);
        }

        // Check for Postmark format
        if ($request->has('FromFull') && $request->has('TextBody')) {
            return $this->parsePostmarkFormat($request);
        }

        // Check for generic/manual format
        if ($request->has('from_email') && $request->has('body')) {
            return $this->parseGenericFormat($request);
        }

        return null;
    }

    /**
     * Parse SendGrid inbound email format
     */
    protected function parseSendGridFormat(Request $request): array
    {
        $from = $this->parseEmailAddress($request->input('from', ''));

        return [
            'from_email' => $from['email'],
            'from_name' => $from['name'],
            'to_email' => $request->input('to'),
            'to_name' => null,
            'subject' => $request->input('subject'),
            'body' => $request->input('text'),
            'body_html' => $request->input('html'),
            'message_id' => $request->header('Message-ID'),
            'in_reply_to' => $this->cleanMessageId($request->header('In-Reply-To')),
            'metadata' => [
                'provider' => 'sendgrid',
                'spam_score' => $request->input('spam_score'),
                'attachments' => $request->input('attachments'),
            ],
        ];
    }

    /**
     * Parse Mailgun inbound email format
     */
    protected function parseMailgunFormat(Request $request): array
    {
        return [
            'from_email' => $request->input('sender'),
            'from_name' => $request->input('from'),
            'to_email' => $request->input('recipient'),
            'to_name' => null,
            'subject' => $request->input('subject'),
            'body' => $request->input('body-plain'),
            'body_html' => $request->input('body-html'),
            'message_id' => $request->input('Message-Id'),
            'in_reply_to' => $this->cleanMessageId($request->input('In-Reply-To')),
            'metadata' => [
                'provider' => 'mailgun',
                'timestamp' => $request->input('timestamp'),
                'token' => $request->input('token'),
            ],
        ];
    }

    /**
     * Parse Postmark inbound email format
     */
    protected function parsePostmarkFormat(Request $request): array
    {
        $fromFull = $request->input('FromFull', []);

        return [
            'from_email' => $fromFull['Email'] ?? $request->input('From'),
            'from_name' => $fromFull['Name'] ?? null,
            'to_email' => $request->input('To'),
            'to_name' => null,
            'subject' => $request->input('Subject'),
            'body' => $request->input('TextBody'),
            'body_html' => $request->input('HtmlBody'),
            'message_id' => $request->input('MessageID'),
            'in_reply_to' => $this->cleanMessageId($request->header('In-Reply-To')),
            'metadata' => [
                'provider' => 'postmark',
                'date' => $request->input('Date'),
            ],
        ];
    }

    /**
     * Parse generic/manual inbound email format
     */
    protected function parseGenericFormat(Request $request): array
    {
        return [
            'from_email' => $request->input('from_email'),
            'from_name' => $request->input('from_name'),
            'to_email' => $request->input('to_email'),
            'to_name' => $request->input('to_name'),
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
            'body_html' => $request->input('body_html'),
            'message_id' => $request->input('message_id'),
            'in_reply_to' => $this->cleanMessageId($request->input('in_reply_to')),
            'metadata' => [
                'provider' => 'manual',
            ],
        ];
    }

    /**
     * Find the original email that this is a reply to
     */
    protected function findOriginalEmail(array $emailData): ?LeadEmail
    {
        // First try to find by In-Reply-To header
        if (!empty($emailData['in_reply_to'])) {
            $email = LeadEmail::where('message_id', $emailData['in_reply_to'])->first();
            if ($email) {
                return $email;
            }
        }

        // Try to find by recipient email (the admin's email who sent the original)
        if (!empty($emailData['to_email'])) {
            // Find the most recent outgoing email to the sender's email
            $email = LeadEmail::where('from_email', $emailData['to_email'])
                ->where('to_email', $emailData['from_email'])
                ->outgoing()
                ->orderBy('created_at', 'desc')
                ->first();

            if ($email) {
                return $email;
            }
        }

        // Try to find lead by sender email and get the most recent outgoing email
        $lead = Lead::where('email', $emailData['from_email'])->first();
        if ($lead) {
            return $lead->emails()
                ->outgoing()
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return null;
    }

    /**
     * Parse an email address string into name and email parts
     */
    protected function parseEmailAddress(string $emailString): array
    {
        // Format: "Name <email@example.com>" or just "email@example.com"
        if (preg_match('/^(.+?)\s*<(.+?)>$/', $emailString, $matches)) {
            return [
                'name' => trim($matches[1], '"\''),
                'email' => $matches[2],
            ];
        }

        return [
            'name' => null,
            'email' => trim($emailString),
        ];
    }

    /**
     * Clean message ID by removing angle brackets
     */
    protected function cleanMessageId(?string $messageId): ?string
    {
        if (!$messageId) {
            return null;
        }

        return trim($messageId, '<>');
    }

    /**
     * Verify webhook signature (implement based on your provider)
     */
    protected function verifyWebhookSignature(Request $request): bool
    {
        // Implement signature verification based on your email provider
        // This is important for security to ensure the webhook is from a trusted source

        // Example for Mailgun:
        // $timestamp = $request->input('timestamp');
        // $token = $request->input('token');
        // $signature = $request->input('signature');
        // $expectedSignature = hash_hmac('sha256', $timestamp . $token, config('services.mailgun.webhook_secret'));
        // return hash_equals($expectedSignature, $signature);

        return true; // For now, accept all - implement proper verification in production
    }
}
