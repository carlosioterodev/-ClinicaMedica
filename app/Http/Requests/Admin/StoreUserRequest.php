<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'nullable|in:active,inactive,suspended',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'dni' => 'required|string|max:20|unique:profiles,dni',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:M,F,O',
            'blood_type' => 'nullable|string|max:5',
            'address' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está registrado.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'roles.required' => 'Debes seleccionar al menos un rol.',
            'roles.*.exists' => 'El rol seleccionado no es válido.',
            'dni.required' => 'El DNI es obligatorio.',
        ];
    }
}
