<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Por favor ingresa tu nombre.',
            'email.required' => 'Por favor ingresa tu correo electrónico.',
            'email.email' => 'El correo electrónico no es válido.',
            'subject.required' => 'Por favor ingresa el asunto.',
            'message.required' => 'Por favor escribe tu mensaje.',
            'message.max' => 'El mensaje no puede exceder los 2000 caracteres.',
        ];
    }
}
