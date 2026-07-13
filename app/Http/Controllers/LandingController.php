<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\ContactMessageReceived;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    public function index()
    {
        $specialties = \App\Models\Specialty::where('is_active', true)->get();
        return view('landing.index', compact('specialties'));
    }

    public function storeContact(StoreContactMessageRequest $request)
    {
        $contactMessage = ContactMessage::create($request->validated());

        Mail::to(config('clinic.email', config('mail.from.address')))
            ->send(new ContactMessageReceived($contactMessage));

        return redirect()->route('landing')
            ->with('contact_success', '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.');
    }
}
