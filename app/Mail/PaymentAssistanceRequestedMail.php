<?php

namespace App\Mail;

use App\Models\ConsultationApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentAssistanceRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConsultationApplication $application,
        public float $amount = 75.00,
        public string $currency = 'GBP',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment assistance requested — '.$this->application->name,
            from: config('mail.from.address', 'contact@p23africa.com'),
            replyTo: [$this->application->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-assistance-requested',
            with: [
                'application' => $this->application,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'adminUrl' => url('/admin/consultations/'.$this->application->id),
            ]
        );
    }
}
