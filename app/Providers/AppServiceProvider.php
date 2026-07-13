<?php

namespace App\Providers;

use App\Services\Appointment\Contracts\AppointmentServiceInterface;
use App\Services\Appointment\AppointmentService;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Observers\AppointmentObserver;
use App\Observers\InvoiceObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AvailabilityService::class, function ($app) {
            return new \App\Services\Appointment\AvailabilityService(
                $app->make(\App\Models\DoctorSchedule::class),
                $app->make(\App\Models\TimeOff::class),
                $app->make(\App\Models\Appointment::class),
            );
        });

        $this->app->bind(AppointmentServiceInterface::class, AppointmentService::class);
    }

    public function boot(): void
    {
        Appointment::observe(AppointmentObserver::class);
        Invoice::observe(InvoiceObserver::class);
    }
}
