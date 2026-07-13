<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Appointment;
use App\Services\Billing\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('patient', 'doctor')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where(function ($sq) use ($request) {
                $sq->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('patient', fn ($p) => $p->where('name', 'like', "%{$request->search}%"));
            }))
            ->when($request->date_from, fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('billing.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $patients = User::whereHas('roles', fn ($q) => $q->where('name', 'patient'))->with('profile')->get();
        $doctors = User::whereHas('roles', fn ($q) => $q->where('name', 'doctor'))->get();
        $appointments = Appointment::with('patient')->latest()->limit(100)->get();

        return view('billing.invoices.create', compact('patients', 'doctors', 'appointments'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'patient.profile', 'doctor', 'appointment']);
        return view('billing.invoices.show', compact('invoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'doctor_id' => 'nullable|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:1',
        ]);

        $invoice = DB::transaction(function () use ($validated) {
            $subtotal = collect($validated['items'])->sum(function ($item) {
                return $item['quantity'] * $item['unit_price'];
            });

            $taxAmount = $subtotal * $validated['tax_rate'];
            $total = $subtotal + $taxAmount;

            $invoice = Invoice::create([
                'patient_id' => $validated['patient_id'],
                'appointment_id' => $validated['appointment_id'] ?? null,
                'doctor_id' => $validated['doctor_id'] ?? null,
                'invoice_number' => Invoice::generateNumber(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $invoice;
        });

        return redirect()->route('billing.invoices.show', $invoice)
            ->with('success', 'Factura generada: ' . $invoice->invoice_number);
    }

    public function markAsPaid(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,transfer,insurance',
        ]);

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $validated['payment_method'],
        ]);

        return back()->with('success', 'Factura marcada como pagada.');
    }

    public function downloadPdf(Invoice $invoice, PDFService $pdfService)
    {
        $pdf = $pdfService->generateInvoicePdf($invoice);
        return $pdf->download("{$invoice->invoice_number}.pdf");
    }
}
