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
            $table->enum('booking_type', ['siswa', 'orang_tua'])->default('siswa');
            $table->text('notes')->nullable();
            $table->enum('status', ['booked', 'completed', 'cancelled'])->default('booked');
            $table->timestamps();

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
