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
        Schema::create('storytelling_themes', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->string('theme_name', 150);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['year', 'month'], 'idx_storytelling_theme_period');
        });

        Schema::create('storytelling_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained('storytelling_themes')->cascadeOnDelete();
            $table->string('title', 150)->nullable();
            $table->text('script_text')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('submitted');
            $table->timestamps();

            $table->index(['student_id', 'theme_id'], 'idx_storytelling_sub_student_theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storytelling_submissions');
        Schema::dropIfExists('storytelling_themes');
    }
};
