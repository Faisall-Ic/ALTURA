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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nisn', 30)->unique();
            $table->foreignId('major_id')->constrained('majors')->restrictOnDelete();
            $table->foreignId('class_id')->constrained('academic_classes')->restrictOnDelete();
            $table->foreignId('mentor_id')->constrained('teachers')->restrictOnDelete();
            $table->foreignId('counselor_id')->constrained('users')->restrictOnDelete();
            $table->string('parent_phone_number', 25)->nullable();
            $table->boolean('status_active')->default(true);
            $table->string('qr_identity_token', 64)->unique();
            $table->string('qr_code_token', 64)->nullable()->unique();
            $table->timestamps();

            $table->index('mentor_id', 'idx_students_mentor');
            $table->index('class_id', 'idx_students_class');
            $table->index('counselor_id', 'idx_students_counselor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
