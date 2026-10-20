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
        Schema::create('alter_courses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_snbt_core')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alter_courses');
    }
};
