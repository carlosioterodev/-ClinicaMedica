<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nuevo mensaje de contacto: {$this->contact->subject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-message',
            with: [
                'contact' => $this->contact,
                'clinicName' => config('clinic.name'),
            ],
        );
    }
}
