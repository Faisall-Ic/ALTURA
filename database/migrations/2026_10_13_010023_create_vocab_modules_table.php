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
        Schema::create('vocab_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('module_number')->unique();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->unsignedInteger('target_word_count')->default(50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocab_modules');
    }
};
