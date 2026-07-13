<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\ScheduleController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Nurse\NurseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/contacto', [LandingController::class, 'storeContact'])->name('contact.store');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::post('/specialties', [SpecialtyController::class, 'store'])->name('specialties.store');
        Route::put('/specialties/{specialty}', [SpecialtyController::class, 'update'])->name('specialties.update');
        Route::delete('/specialties/{specialty}', [SpecialtyController::class, 'destroy'])->name('specialties.destroy');

        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // Doctor
    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('appointments.status');

        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
        Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

        Route::get('/time-off', [ScheduleController::class, 'timeOffIndex'])->name('time-off.index');
        Route::post('/time-off', [ScheduleController::class, 'storeTimeOff'])->name('time-off.store');

        Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
        Route::get('/medical-records/{record}', [MedicalRecordController::class, 'show'])->name('medical-records.show');
        Route::get('/appointments/{appointment}/medical-record/create', [MedicalRecordController::class, 'createForAppointment'])->name('medical-records.create');
        Route::post('/appointments/{appointment}/medical-record', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::post('/appointments/{appointment}/notes', [MedicalRecordController::class, 'storeNote'])->name('notes.store');
        Route::post('/medical-records/{record}/prescriptions', [MedicalRecordController::class, 'storePrescription'])->name('medical-records.prescriptions.store');
        Route::delete('/medical-records/{record}/prescriptions/{prescription}', [MedicalRecordController::class, 'destroyPrescription'])->name('medical-records.prescriptions.destroy');
    });

    // Patient
    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/profile/{patient}/historial', [PatientController::class, 'medicalHistory'])->name('medical-history');
        Route::post('/profile/{patient}/allergies', [PatientController::class, 'storeAllergy'])->name('allergies.store');
        Route::delete('/profile/{patient}/allergies/{allergy}', [PatientController::class, 'destroyAllergy'])->name('allergies.destroy');
        Route::post('/profile/{patient}/conditions', [PatientController::class, 'storeCondition'])->name('conditions.store');
        Route::delete('/profile/{patient}/conditions/{condition}', [PatientController::class, 'destroyCondition'])->name('conditions.destroy');

        Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
        Route::patch('/appointments/{id}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::get('/appointments/slots', [PatientAppointmentController::class, 'availableSlots'])->name('appointments.slots');

        Route::get('/invoices', [PatientAppointmentController::class, 'invoices'])->name('invoices.index');
    });

    // Nurse
    Route::middleware(['role:nurse'])->prefix('nurse')->name('nurse.')->group(function () {
        Route::get('/dashboard', [NurseController::class, 'index'])->name('dashboard');
        Route::get('/appointment/{appointment}/patient', [NurseController::class, 'showPatient'])->name('patient-detail');
        Route::post('/appointment/{appointment}/vital-signs', [NurseController::class, 'storeVitalSigns'])->name('vital-signs.store');
        Route::post('/triage/{appointment}', [NurseController::class, 'triage'])->name('triage');
    });

    // Billing
    Route::middleware(['role:admin'])->prefix('billing')->name('billing.')->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'markAsPaid'])->name('invoices.pay');
        Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    });
});
