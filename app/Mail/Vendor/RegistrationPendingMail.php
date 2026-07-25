<?php

namespace App\Mail\Vendor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receipentName;

    public function __construct($receipentName)
    {
        $this->receipentName = $receipentName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Pending Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.vendors.registration-pending',
            with: [
                'receipent_name' => $this->receipentName,
                'header_subtitle' => 'Registration Pending Mail',
                'seller_guide_url' => route('static.sellerGuide'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
