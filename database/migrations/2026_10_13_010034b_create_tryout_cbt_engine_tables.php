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
        Schema::create('tryout_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_id')->constrained('tryouts')->cascadeOnDelete();
            $table->string('subtest_code', 20); // TPS, LBI, LBS, PBM, PM, PU, PK, PPU
            $table->text('question_text');
            $table->json('options'); // ['A' => '...', 'B' => '...', ...]
            $table->string('correct_answer', 10);
            $table->text('explanation')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->index(['tryout_id', 'subtest_code'], 'idx_tryout_questions_subtest');
        });

        Schema::create('tryout_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('tryout_id')->constrained('tryouts')->cascadeOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamps();

            $table->index(['student_id', 'tryout_id', 'status'], 'idx_tryout_attempts_student');
        });

        Schema::create('tryout_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('tryout_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('tryout_questions')->cascadeOnDelete();
            $table->string('student_answer', 10)->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id'], 'uq_attempt_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_answers');
        Schema::dropIfExists('tryout_attempts');
        Schema::dropIfExists('tryout_questions');
    }
};
