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
        Schema::create('monthly_submission_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('teachers')->cascadeOnDelete();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->json('missing_tasks'); // ['vocab', 'storytelling', 'wacana', 'alter']
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();

            $table->index(['student_id', 'month', 'year'], 'idx_monthly_alerts_student');
            $table->index(['mentor_id', 'is_resolved'], 'idx_monthly_alerts_mentor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_submission_alerts');
    }
};
