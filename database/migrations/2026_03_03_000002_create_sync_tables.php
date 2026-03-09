<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_queue', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->enum('action', ['create', 'update', 'delete']);
            $table->json('payload');
            $table->enum('status', ['pending', 'syncing', 'synced', 'failed'])->default('pending');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'attempts']);
            $table->index(['model_type', 'model_id']);
        });

        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sync_queue_id')->nullable()->constrained('sync_queue')->nullOnDelete();
            $table->enum('status', ['success', 'failed', 'partial']);
            $table->unsignedInteger('records_pushed')->default(0);
            $table->unsignedInteger('records_pulled')->default(0);
            $table->text('message')->nullable();
            $table->json('details')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
        Schema::dropIfExists('sync_queue');
    }
};
