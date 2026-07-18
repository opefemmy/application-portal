<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShortlistEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $data;

    public function __construct(Application $application, array $data)
    {
        $this->application = $application;
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Congratulations! You Have Been Shortlisted - {$this->application->application_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.shortlist',
        );
    }
}