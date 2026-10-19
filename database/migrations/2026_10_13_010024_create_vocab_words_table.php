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
        Schema::create('vocab_words', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('module_number');
            $table->string('english_word', 100);
            $table->string('indonesian_meaning', 150);
            $table->json('acceptable_answers')->nullable();
            $table->text('example_sentence')->nullable();
            $table->timestamps();

            $table->index('module_number', 'idx_vocab_module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocab_words');
    }
};
