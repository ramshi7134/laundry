<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('total_credited', 12, 2)->default(0);
            $table->decimal('total_debited', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['credit', 'debit', 'refund']);
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->decimal('balance_after', 12, 2);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['customer_id', 'type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('customer_wallets');
    }
};
