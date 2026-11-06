<?php

namespace App\Mail;

use App\Models\ConsultationApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentAssistanceInstructionsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConsultationApplication $application,
        public string $instructions,
        public float $amount = 75.00,
        public string $currency = 'GBP',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alternative payment option for your P23 Africa consultation',
            from: config('mail.from.address', 'contact@p23africa.com'),
            replyTo: [config('mail.from.address', 'contact@p23africa.com')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-assistance-instructions',
            with: [
                'application' => $this->application,
                'instructions' => $this->instructions,
                'amount' => $this->amount,
                'currency' => $this->currency,
            ]
        );
    }
}
