<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        \Log::info("Factura generada: {$invoice->invoice_number}", [
            'patient_id' => $invoice->patient_id,
            'total' => $invoice->total,
        ]);
    }

    public function updated(Invoice $invoice): void
    {
        if ($invoice->isDirty('status') && $invoice->status === 'paid') {
            \Log::info("Factura pagada: {$invoice->invoice_number}", [
                'paid_at' => $invoice->paid_at,
                'payment_method' => $invoice->payment_method,
            ]);
        }
    }
}
