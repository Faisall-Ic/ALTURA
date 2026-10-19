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
        Schema::create('speaking_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('speaking_bookings')->cascadeOnDelete();
            $table->decimal('grade', 5, 2);
            $table->json('rubric_scores')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaking_assessments');
    }
};
