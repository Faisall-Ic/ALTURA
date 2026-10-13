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
        Schema::create('counseling_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('date_time');
            $table->string('room_or_link')->default('Ruang BK Alto / Tatap Muka');
            $table->unsignedInteger('max_quota')->default(1);
            $table->unsignedInteger('remaining_quota')->default(1);
            $table->timestamps();

            $table->index(['counselor_user_id', 'date_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_slots');
    }
};
