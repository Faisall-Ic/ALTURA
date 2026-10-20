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
        Schema::create('tryout_subtests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_id')->constrained('tryouts')->cascadeOnDelete();
            $table->string('name', 100);
            $table->unsignedInteger('question_count')->default(20);
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_subtests');
    }
};
