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
        Schema::create('subject_consultation_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('topic', 150);
            $table->dateTime('date_time');
            $table->string('room_or_link')->default('Ruang Tentoring Alto');
            $table->unsignedInteger('max_quota')->default(5);
            $table->unsignedInteger('remaining_quota')->default(5);
            $table->timestamps();

            $table->index(['subject_id', 'date_time']);
            $table->index(['teacher_id', 'date_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_consultation_slots');
    }
};
