@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Gestión de Usuarios</h1>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        + Nuevo Usuario
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($user->roles as $role)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ match($user->status) {
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-gray-100 text-gray-800',
                                    'suspended' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                } }}">
                                {{ match($user->status) {
                                    'active' => 'Activo',
                                    'inactive' => 'Inactivo',
                                    'suspended' => 'Suspendido',
                                    default => $user->status,
                                } }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline text-xs">Ver</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-amber-600 hover:underline text-xs">Editar</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $user->name }}? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline text-xs">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 border-t border-gray-200">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
