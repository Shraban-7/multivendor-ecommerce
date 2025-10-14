<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receipentName;
    public $verificationCode;
    public $expiryMinutes;

    public function __construct($receipentName, $verificationCode, $expiryMinutes)
    {
        $this->receipentName = $receipentName;
        $this->verificationCode = $verificationCode;
        $this->expiryMinutes = $expiryMinutes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.email-verification',
            with: [
                'receipent_name' => $this->receipentName,
                'verification_code' => $this->verificationCode,
                'expiry_minutes' => $this->expiryMinutes,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
