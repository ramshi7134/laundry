<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->decimal('quantity', 10, 3)->default(0);
            $table->string('unit')->default('pcs'); // pcs, kg, liters, boxes
            $table->decimal('minimum_level', 10, 3)->default(0);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->boolean('alert_sent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['branch_id', 'is_active']);
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->decimal('quantity', 10, 3);
            $table->enum('type', ['restock', 'usage', 'adjustment', 'waste']);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['item_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_items');
    }
};
