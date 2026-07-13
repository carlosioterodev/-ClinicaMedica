@extends('layouts.app')

@section('title', 'Especialidades')

@section('content')
<h1 class="text-2xl font-bold mb-6">Especialidades</h1>

<div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
    <h2 class="font-semibold mb-3">Nueva Especialidad</h2>
    <form method="POST" action="{{ route('admin.specialties.store') }}" class="flex gap-3 items-end">
        @csrf
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="name" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <input type="text" name="description" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Agregar</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($specialties as $specialty)
                <tr>
                    <td class="px-6 py-4">{{ $specialty->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $specialty->slug }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs rounded-full {{ $specialty->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $specialty->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.specialties.destroy', $specialty) }}" class="inline" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay especialidades.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-3">{{ $specialties->links() }}</div>
</div>
@endsection
