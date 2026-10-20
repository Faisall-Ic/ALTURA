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
        Schema::create('consultation_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('consultation_slots')->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['student_mapel', 'parent_bk'])->default('student_mapel');
            $table->enum('status', ['confirmed', 'attended', 'cancelled'])->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_bookings');
    }
};
