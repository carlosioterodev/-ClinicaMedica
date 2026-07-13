@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Editar: {{ $user->name }}</h1>
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">← Volver</a>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4">Datos de Cuenta</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspendido</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select name="roles[]" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ in_array($role->name, old('roles', $userRoles)) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h2 class="text-lg font-semibold mb-4">Datos Personales</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                <input type="text" name="dni" value="{{ old('dni', $user->profile->dni ?? '') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->profile->date_of_birth?->format('Y-m-d') ?? '') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
                <select name="gender" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccionar...</option>
                    <option value="M" {{ old('gender', $user->profile->gender ?? '') === 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('gender', $user->profile->gender ?? '') === 'F' ? 'selected' : '' }}>Femenino</option>
                    <option value="O" {{ old('gender', $user->profile->gender ?? '') === 'O' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Sangre</label>
                <select name="blood_type" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccionar...</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                        <option value="{{ $bt }}" {{ old('blood_type', $user->profile->blood_type ?? '') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <textarea name="address" rows="2"
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('address', $user->profile->address ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-2">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            Guardar Cambios
        </button>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
            Cancelar
        </a>
    </div>
</form>
@endsection
