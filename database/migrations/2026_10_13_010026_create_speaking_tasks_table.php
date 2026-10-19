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
        Schema::create('speaking_tasks', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['storytelling', 'wacana']);
            $table->string('title', 150);
            $table->longText('script_text');
            $table->decimal('minimum_pass_grade', 5, 2)->default(75.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaking_tasks');
    }
};
