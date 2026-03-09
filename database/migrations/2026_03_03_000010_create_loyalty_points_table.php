<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('points');
            $table->enum('type', ['earn', 'redeem', 'expire', 'adjust']);
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->integer('balance_after')->default(0);
            $table->timestamps();

            $table->index(['customer_id', 'type']);
        });

        // Add loyalty balance column to customers
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('loyalty_balance')->default(0)->after('phone');
            $table->integer('total_loyalty_earned')->default(0)->after('loyalty_balance');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['loyalty_balance', 'total_loyalty_earned']);
        });
        Schema::dropIfExists('loyalty_points');
    }
};
