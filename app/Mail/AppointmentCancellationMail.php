<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCancellationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Cancelación de Cita — " . config('clinic.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-cancellation',
            with: [
                'appointment' => $this->appointment,
                'clinicName' => config('clinic.name'),
            ],
        );
    }
}
