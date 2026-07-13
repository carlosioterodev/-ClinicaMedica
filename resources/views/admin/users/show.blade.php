@extends('layouts.app')

@section('title', $user->name . ' - Detalle')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
        @foreach($user->roles as $role)
            <span class="px-3 py-1 text-sm font-semibold rounded-full
                {{ match($role->name) {
                    'admin' => 'bg-purple-100 text-purple-800',
                    'doctor' => 'bg-blue-100 text-blue-800',
                    'nurse' => 'bg-green-100 text-green-800',
                    'patient' => 'bg-gray-100 text-gray-800',
                    default => 'bg-gray-100 text-gray-800',
                } }}">
                {{ ucfirst($role->name) }}
            </span>
        @endforeach
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition text-sm">
            Editar
        </a>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline text-sm self-center">← Volver</a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos de Cuenta</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="font-medium text-gray-500">Nombre:</dt>
                <dd>{{ $user->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="font-medium text-gray-500">Email:</dt>
                <dd>{{ $user->email }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="font-medium text-gray-500">Estado:</dt>
                <dd>
                    <span class="px-2 py-1 text-xs rounded-full
                        {{ match($user->status) {
                            'active' => 'bg-green-100 text-green-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                            'suspended' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        } }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="font-medium text-gray-500">Verificado:</dt>
                <dd>{{ $user->email_verified_at ? 'Sí' : 'No' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="font-medium text-gray-500">Registro:</dt>
                <dd>{{ $user->created_at->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Datos Personales</h2>
        @if($user->profile)
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">DNI:</dt>
                    <dd>{{ $user->profile->dni ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Teléfono:</dt>
                    <dd>{{ $user->profile->phone ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Fecha Nac.:</dt>
                    <dd>{{ $user->profile->date_of_birth?->format('d/m/Y') ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Edad:</dt>
                    <dd>{{ $user->profile->age ?? 'N/A' }} años</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Género:</dt>
                    <dd>{{ $user->profile->gender ? match($user->profile->gender) { 'M' => 'Masculino', 'F' => 'Femenino', 'O' => 'Otro', default => $user->profile->gender } : 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Tipo Sangre:</dt>
                    <dd>{{ $user->profile->blood_type ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="font-medium text-gray-500">Dirección:</dt>
                    <dd class="text-right max-w-xs">{{ $user->profile->address ?? 'N/A' }}</dd>
                </div>
            </dl>
        @else
            <p class="text-gray-500 text-sm">No hay perfil registrado.</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-lg font-semibold mb-4 border-b pb-2">Actividad</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="font-medium text-gray-500">Citas como paciente:</dt>
                <dd class="font-bold text-lg">{{ $user->appointmentsAsPatient->count() }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="font-medium text-gray-500">Citas como médico:</dt>
                <dd class="font-bold text-lg">{{ $user->appointmentsAsDoctor->count() }}</dd>
            </div>
        </dl>
    </div>

    @if($user->id !== auth()->id())
        <div class="bg-white p-6 rounded-lg shadow-sm border border-red-200">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2 text-red-600">Zona de Peligro</h2>
            <p class="text-sm text-gray-500 mb-4">Eliminar este usuario eliminará todos sus datos permanentemente. Esta acción no se puede deshacer.</p>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿ELIMINAR a {{ $user->name }}? Todos sus datos se perderán permanentemente.')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm">
                    Eliminar Usuario
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
