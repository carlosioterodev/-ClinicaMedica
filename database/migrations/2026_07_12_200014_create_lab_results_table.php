<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            $table->string('test_name');
            $table->string('result_value')->nullable();
            $table->string('reference_range')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('is_normal')->nullable();
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('performed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
