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
        Schema::create('tryout_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('tryout_id')->constrained('tryouts')->cascadeOnDelete();
            $table->decimal('subtest_tps', 6, 2)->default(0.00);
            $table->decimal('subtest_indo', 6, 2)->default(0.00);
            $table->decimal('subtest_inggris', 6, 2)->default(0.00);
            $table->decimal('subtest_matematika', 6, 2)->default(0.00);
            $table->decimal('average_score', 6, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['student_id', 'tryout_id'], 'uq_student_tryout');
            $table->index(['tryout_id', 'average_score'], 'idx_tryout_average');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_scores');
    }
};
