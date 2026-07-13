@php
    $role = Auth::user()->getRoleNames()->first();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if($role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            Usuarios
                        </x-nav-link>
                        <x-nav-link :href="route('admin.appointments.index')" :active="request()->routeIs('admin.appointments.*')">
                            Citas
                        </x-nav-link>
                        <x-nav-link :href="route('billing.invoices.index')" :active="request()->routeIs('billing.*')">
                            Facturación
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Reportes
                        </x-nav-link>
                    @elseif($role === 'doctor')
                        <x-nav-link :href="route('doctor.dashboard')" :active="request()->routeIs('doctor.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('doctor.appointments.index')" :active="request()->routeIs('doctor.appointments.*')">
                            Citas
                        </x-nav-link>
                        <x-nav-link :href="route('doctor.medical-records.index')" :active="request()->routeIs('doctor.medical-records.*')">
                            Historial
                        </x-nav-link>
                        <x-nav-link :href="route('doctor.schedule.index')" :active="request()->routeIs('doctor.schedule.*')">
                            Horario
                        </x-nav-link>
                    @elseif($role === 'nurse')
                        <x-nav-link :href="route('nurse.dashboard')" :active="request()->routeIs('nurse.dashboard')">
                            Dashboard
                        </x-nav-link>
                    @elseif($role === 'patient')
                        <x-nav-link :href="route('patient.dashboard')" :active="request()->routeIs('patient.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('patient.appointments.index')" :active="request()->routeIs('patient.appointments.*')">
                            Mis Citas
                        </x-nav-link>
                        <x-nav-link :href="route('patient.show', auth()->id())" :active="request()->routeIs('patient.show') || request()->routeIs('patient.medical-history')">
                            Mi Perfil
                        </x-nav-link>
                        <x-nav-link :href="route('patient.invoices.index')" :active="request()->routeIs('patient.invoices.*')">
                            Mis Facturas
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="me-3 text-sm text-gray-500">
                    {{ Auth::user()->name }}
                    <span class="px-2 py-0.5 ms-1 text-xs rounded-full
                        {{ match($role) {
                            'admin' => 'bg-purple-100 text-purple-800',
                            'doctor' => 'bg-blue-100 text-blue-800',
                            'nurse' => 'bg-green-100 text-green-800',
                            'patient' => 'bg-gray-100 text-gray-600',
                            default => 'bg-gray-100 text-gray-800',
                        } }}">
                        {{ ucfirst($role) }}
                    </span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ __('Cuenta') }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mi Perfil') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('dashboard')">
                            {{ __('Dashboard') }}
                        </x-dropdown-link>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if($role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Usuarios</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.appointments.index')" :active="request()->routeIs('admin.appointments.*')">Citas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('billing.invoices.index')" :active="request()->routeIs('billing.*')">Facturación</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Reportes</x-responsive-nav-link>
            @elseif($role === 'doctor')
                <x-responsive-nav-link :href="route('doctor.dashboard')" :active="request()->routeIs('doctor.dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('doctor.appointments.index')" :active="request()->routeIs('doctor.appointments.*')">Citas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('doctor.medical-records.index')" :active="request()->routeIs('doctor.medical-records.*')">Historial</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('doctor.schedule.index')" :active="request()->routeIs('doctor.schedule.*')">Horario</x-responsive-nav-link>
            @elseif($role === 'nurse')
                <x-responsive-nav-link :href="route('nurse.dashboard')" :active="request()->routeIs('nurse.dashboard')">Dashboard</x-responsive-nav-link>
            @elseif($role === 'patient')
                <x-responsive-nav-link :href="route('patient.dashboard')" :active="request()->routeIs('patient.dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('patient.appointments.index')" :active="request()->routeIs('patient.appointments.*')">Mis Citas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('patient.show', auth()->id())" :active="request()->routeIs('patient.show')">Mi Perfil</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('patient.invoices.index')" :active="request()->routeIs('patient.invoices.*')">Mis Facturas</x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ ucfirst($role) }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Mi Perfil</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
