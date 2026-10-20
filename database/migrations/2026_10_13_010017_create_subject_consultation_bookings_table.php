<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subject_consultation_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('subject_consultation_slots')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('status', ['booked', 'attended', 'cancelled'])->default('booked');
            $table->timestamps();

            $table->unique(['slot_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_consultation_bookings');
    }
};
