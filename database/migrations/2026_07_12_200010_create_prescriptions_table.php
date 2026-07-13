<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('medication_id')->nullable()->constrained()->nullOnDelete();
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->text('instructions')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
