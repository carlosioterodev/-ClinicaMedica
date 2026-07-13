<div>
    @forelse($appointments as $appointment)
        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b' : '' }}">
            <div>
                <p class="font-medium">{{ $appointment->patient->name }}</p>
                <p class="text-sm text-gray-500">{{ $appointment->specialty->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium">{{ $appointment->scheduled_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-500">{{ $appointment->scheduled_at->format('H:i') }}</p>
            </div>
            <a href="{{ route('doctor.appointments.show', $appointment) }}"
               class="text-blue-600 text-sm hover:underline ml-4">Ver</a>
        </div>
    @empty
        <p class="text-gray-500 text-center py-4">No hay citas próximas.</p>
    @endforelse
</div>
