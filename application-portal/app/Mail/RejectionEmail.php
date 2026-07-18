<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $message;

    public function __construct(Application $application, ?string $message = null)
    {
        $this->application = $application;
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Application Status Update - {$this->application->application_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rejection',
        );
    }
}