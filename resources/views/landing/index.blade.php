@extends('layouts.public')

@section('title', 'Bienvenido - ' . config('clinic.name'))

@section('content')
<div class="min-h-screen flex flex-col">
    <header class="bg-blue-600 text-white py-20">
        <div class="max-w-5xl mx-auto text-center px-4">
            <h1 class="text-4xl font-bold mb-4">{{ config('clinic.name') }}</h1>
            <p class="text-xl mb-8">Tu salud en buenas manos</p>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Ir al Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Iniciar Sesión
                </a>
            @endauth
        </div>
    </header>

    @if(session('contact_success'))
        <div class="max-w-5xl mx-auto mt-6 px-4 w-full">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('contact_success') }}</div>
        </div>
    @endif

    <section class="py-16 flex-1">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8">Nuestras Especialidades</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($specialties as $specialty)
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <h3 class="text-lg font-semibold mb-2">{{ $specialty->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $specialty->description }}</p>
                    </div>
                @empty
                    <p class="col-span-3 text-gray-500">Próximamente tendremos especialidades disponibles.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="contacto" class="py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold mb-2 text-center">Contáctanos</h2>
            <p class="text-gray-600 text-center mb-8">¿Tienes preguntas? Envíanos un mensaje y te responderemos pronto.</p>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="bg-white p-8 rounded-lg shadow-sm border space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Tu nombre completo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="correo@ejemplo.com">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Opcional">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asunto <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="¿En qué podemos ayudarte?">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="4" required
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Escribe tu mensaje aquí...">{{ old('message') }}</textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <p>{{ config('clinic.address') }}</p>
            <p>{{ config('clinic.phone') }} | {{ config('clinic.email') }}</p>
        </div>
    </footer>
</div>
@endsection
