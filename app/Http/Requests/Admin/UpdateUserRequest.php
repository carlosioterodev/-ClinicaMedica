<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'status' => 'required|in:active,inactive,suspended',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'dni' => ['nullable', 'string', 'max:20', Rule::unique('profiles')->ignore($this->route('user')->profile?->id)],
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
            'email.unique' => 'Este correo ya está en uso por otro usuario.',
            'status.required' => 'Debes seleccionar un estado.',
            'roles.required' => 'Debes seleccionar al menos un rol.',
        ];
    }
}
