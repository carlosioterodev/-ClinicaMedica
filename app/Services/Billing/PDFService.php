<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{
    public function generateInvoicePdf(Invoice $invoice): \Barryvdh\DomPDF\PDF
    {
        $invoice->load(['items', 'patient.profile', 'doctor', 'appointment']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'clinic' => [
                'name' => config('clinic.name', 'Clínica'),
                'address' => config('clinic.address', ''),
                'phone' => config('clinic.phone', ''),
                'email' => config('clinic.email', ''),
            ],
        ]);

        $pdf->setPaper(
            config('clinic.pdf.paper_size', 'letter'),
            config('clinic.pdf.orientation', 'portrait')
        );

        return $pdf;
    }
}
