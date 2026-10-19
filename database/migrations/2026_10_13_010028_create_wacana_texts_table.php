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
        Schema::create('wacana_texts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->longText('content');
            $table->unsignedInteger('paragraph_count')->default(1);
            $table->timestamps();
        });

        Schema::create('wacana_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('wacana_id')->constrained('wacana_texts')->cascadeOnDelete();
            $table->string('status', 30)->default('submitted');
            $table->timestamps();

            $table->index(['student_id', 'wacana_id'], 'idx_wacana_sub_student_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wacana_submissions');
        Schema::dropIfExists('wacana_texts');
    }
};
