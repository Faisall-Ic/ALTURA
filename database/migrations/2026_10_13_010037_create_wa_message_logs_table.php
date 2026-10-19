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
        Schema::create('wa_message_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 25)->nullable();
            $table->string('parent_phone_number', 25)->nullable();
            $table->text('message_payload');
            $table->string('trigger_event', 50);
            $table->enum('status', ['sent', 'failed', 'queued'])->default('queued');
            $table->text('error_response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['parent_phone_number', 'status'], 'idx_wa_phone_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_message_logs');
    }
};
