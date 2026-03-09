<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_staff_id')->constrained('users')->cascadeOnDelete();
            $table->string('pickup_address')->nullable();
            $table->string('delivery_address')->nullable();
            $table->timestamp('scheduled_pickup_at')->nullable();
            $table->timestamp('scheduled_delivery_at')->nullable();
            $table->timestamp('actual_pickup_at')->nullable();
            $table->timestamp('actual_delivery_at')->nullable();
            $table->enum('status', ['assigned', 'picked_up', 'in_transit', 'delivered', 'failed'])->default('assigned');
            $table->text('notes')->nullable();
            $table->string('proof_photo')->nullable();
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['status', 'delivery_staff_id']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_assignments');
    }
};
