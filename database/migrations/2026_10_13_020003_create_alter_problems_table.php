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
        Schema::create('alter_problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('alter_chapters')->cascadeOnDelete();
            $table->unsignedInteger('problem_number');
            $table->string('type_name', 50)->default('Uraian');
            $table->string('problem_image_path', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alter_problems');
    }
};
