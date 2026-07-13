<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->unsignedSmallInteger('duration_minutes')->default(30);
            $table->enum('status', [
                'scheduled', 'confirmed', 'in_progress',
                'completed', 'cancelled', 'no_show',
            ])->default('scheduled');
            $table->text('reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['doctor_id', 'scheduled_at', 'status']);
            $table->index(['patient_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
