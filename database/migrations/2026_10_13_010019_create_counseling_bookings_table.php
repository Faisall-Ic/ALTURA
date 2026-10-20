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
        Schema::create('counseling_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('counseling_slots')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $table->unsignedTinyInteger('semester')->default(1);
            $table->unsignedSmallInteger('year')->default(2026);
            $table->enum('booking_type', ['siswa', 'orang_tua'])->default('siswa');
            $table->text('notes')->nullable();
            $table->enum('status', ['booked', 'attended', 'completed', 'cancelled'])->default('booked');
            $table->timestamps();
            $table->index(['student_id', 'semester', 'year'], 'idx_counseling_student_semester_year');

            $table->index(['slot_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_bookings');
    }
};
