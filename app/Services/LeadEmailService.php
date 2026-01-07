<?php

namespace App\Services;

use App\Mail\LeadMail;
use App\Models\LeadEmail;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LeadEmailService
{
    /**
     * Send an email to a lead using the admin user's Google Workspace email
     * 
     * For Google Workspace, the authenticated user's email will be used as the "From" address.
     * This requires that:
     * 1. The user's email is part of your Google Workspace domain
     * 2. The SMTP account has "Send As" permission for that email, OR
     * 3. You're using domain-wide delegation with a service account
     */
    public function sendEmail(LeadEmail $leadEmail, User $sender): bool
    {
        try {
            // Configure the mailer to use the sender's email
            $this->configureMailerForUser($sender);

            // Send the email
            Mail::to($leadEmail->to_email, $leadEmail->to_name)
                ->send(new LeadMail($leadEmail, $sender));

            // Reset to default configuration
            $this->resetMailerConfig();

            Log::info('Lead email sent successfully', [
                'lead_email_id' => $leadEmail->id,
                'sender_email' => $sender->email,
                'recipient_email' => $leadEmail->to_email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send lead email via Google Workspace', [
                'lead_email_id' => $leadEmail->id,
                'sender_id' => $sender->id,
                'sender_email' => $sender->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Reset to default configuration
            $this->resetMailerConfig();

            throw $e;
        }
    }

    /**
     * Configure the mailer to send from the user's email address
     * 
     * For Google Workspace with shared SMTP credentials:
     * - The "From" header is set to the user's email
     * - Google Workspace allows this if "Send As" is configured in Gmail settings
     * - The authenticated SMTP user (MAIL_USERNAME) must have permission to send as this user
     * 
     * To enable this in Google Workspace Admin Console:
     * 1. Go to Google Admin Console > Apps > Google Workspace > Gmail > User Settings
     * 2. Enable "Allow users to send mail as any alias in this domain"
     * OR
     * 3. Configure specific "Send As" aliases for each user in their Gmail settings
     */
    protected function configureMailerForUser(User $sender): void
    {
        // Set the from address to the sender's email
        // This works when:
        // 1. The sender's email is on the same Google Workspace domain
        // 2. The SMTP account has delegated access or "Send As" permission
        Config::set('mail.from.address', $sender->email);
        Config::set('mail.from.name', $sender->name);
        
        // Log the configuration change for debugging
        Log::debug('Mail configuration updated for user', [
            'from_address' => $sender->email,
            'from_name' => $sender->name,
            'smtp_username' => config('mail.mailers.smtp.username'),
        ]);
    }

    /**
     * Reset mailer configuration to defaults
     */
    protected function resetMailerConfig(): void
    {
        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS'));
        Config::set('mail.from.name', env('MAIL_FROM_NAME'));
    }

    /**
     * Validate that a user can send emails
     * 
     * Checks if the user's email domain is allowed for sending
     */
    public function canUserSendEmail(User $user): bool
    {
        // Check if the user has a valid email address
        if (empty($user->email)) {
            return false;
        }

        // Check if the email belongs to your Google Workspace domain
        $allowedDomains = $this->getAllowedDomains();
        
        // If no domains are configured, allow all (not recommended for production)
        if (empty($allowedDomains)) {
            return true;
        }

        $userDomain = $this->getEmailDomain($user->email);
        
        return in_array($userDomain, $allowedDomains);
    }

    /**
     * Get the domain part of an email address
     */
    protected function getEmailDomain(string $email): string
    {
        $parts = explode('@', $email);
        return strtolower($parts[1] ?? '');
    }

    /**
     * Get the list of allowed sending domains
     */
    public function getAllowedDomains(): array
    {
        return config('services.google_workspace.allowed_domains', []);
    }

    /**
     * Check if an email domain is allowed
     */
    public function isDomainAllowed(string $email): bool
    {
        $allowedDomains = $this->getAllowedDomains();
        
        if (empty($allowedDomains)) {
            return true;
        }

        $domain = $this->getEmailDomain($email);
        return in_array($domain, $allowedDomains);
    }
}
