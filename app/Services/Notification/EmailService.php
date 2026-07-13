<?php

namespace App\Services\Notification;

use App\Models\Invoice;
use App\Models\NotificationLog;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendInvoiceEmail(Invoice $invoice): void
    {
        $to = $invoice->patient->email;

        Mail::to($to)->send(new InvoiceMail($invoice));

        NotificationLog::create([
            'user_id' => $invoice->patient_id,
            'channel' => 'email',
            'subject' => "Factura {$invoice->invoice_number}",
            'body' => "Se envió la factura por correo electrónico.",
            'sent_at' => now(),
            'status' => 'sent',
        ]);
    }
}
