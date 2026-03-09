<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('channel', ['whatsapp', 'sms', 'email', 'push']);
            $table->string('recipient');
            $table->string('template')->nullable();
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered']);
            $table->string('provider_message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'channel']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
