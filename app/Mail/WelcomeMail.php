<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
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
            subject: 'Welcome to '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'receipent_name' => $this->receipentName,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
