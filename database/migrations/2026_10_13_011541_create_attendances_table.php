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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('date');
            $table->time('session_time');
            $table->enum('status', ['hadir', 'terlambat', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->foreignId('scanned_by_teacher_id')->constrained('teachers')->restrictOnDelete();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date', 'session_time'], 'uq_student_date_session');
            $table->index(['date', 'status'], 'idx_attendances_date_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
