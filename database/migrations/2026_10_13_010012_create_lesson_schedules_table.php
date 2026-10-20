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
        Schema::create('lesson_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('room', 50)->nullable();
            $table->string('day_of_week', 20); // Senin, Selasa, Rabu, Kamis, Jumat, Sabtu
            $table->time('time_start');
            $table->time('time_end');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['class_id', 'day_of_week']);
            $table->index(['teacher_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_schedules');
    }
};
