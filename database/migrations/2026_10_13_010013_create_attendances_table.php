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
            $table->foreignId('lesson_schedule_id')->nullable()->constrained('lesson_schedules')->nullOnDelete();
            $table->date('date');
            $table->time('time_in')->nullable()->default('08:00:00');
            $table->time('session_time')->nullable()->default('08:00:00');
            $table->enum('status', ['hadir', 'terlambat', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->foreignId('scanned_by_admin_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('scanned_by_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('remarks')->nullable();
            $table->timestamps();

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
