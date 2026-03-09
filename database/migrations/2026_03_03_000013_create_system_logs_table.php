<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['debug', 'info', 'warning', 'error', 'critical']);
            $table->string('message');
            $table->string('source')->nullable(); // sync, notification, payment, etc.
            $table->json('context')->nullable();
            $table->string('trace')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['level', 'source']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
