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
        Schema::create('consultation_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('topic', 150);
            $table->dateTime('date_time');
            $table->unsignedInteger('max_quota')->default(5);
            $table->unsignedInteger('remaining_quota')->default(5);
            $table->string('location_or_link')->default('Ruang Konsultasi Alto');
            $table->timestamps();

            $table->index('date_time', 'idx_consultation_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_slots');
    }
};
