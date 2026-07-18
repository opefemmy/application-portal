<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        $portalName = \App\Models\Setting::get('portal_name', 'Application Portal');
        return new Envelope(
            subject: "Application Received Successfully - {$this->application->application_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-received',
        );
    }
}