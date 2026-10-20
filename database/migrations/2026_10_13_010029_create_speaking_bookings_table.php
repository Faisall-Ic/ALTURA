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
        Schema::create('speaking_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('teachers')->restrictOnDelete();
            $table->enum('task_type', ['storytelling', 'wacana'])->default('storytelling');
            $table->foreignId('speaking_task_id')->nullable()->constrained('speaking_tasks')->nullOnDelete();
            $table->date('booking_date');
            $table->string('time_slot', 30);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->decimal('grade', 5, 2)->nullable();
            $table->unsignedInteger('rubric_pronunciation')->nullable();
            $table->unsignedInteger('rubric_fluency')->nullable();
            $table->unsignedInteger('rubric_vocabulary')->nullable();
            $table->unsignedInteger('rubric_comprehension')->nullable();
            $table->text('rubric_notes')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->index(['mentor_id', 'booking_date'], 'idx_speaking_mentor_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaking_bookings');
    }
};
