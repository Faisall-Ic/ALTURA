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
        Schema::create('alter_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('alter_courses')->cascadeOnDelete();
            $table->unsignedInteger('chapter_number');
            $table->string('title', 150);
            $table->string('youtube_video_id', 50);
            $table->timestamps();

            $table->index(['course_id', 'chapter_number'], 'idx_chapters_course');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alter_chapters');
    }
};
