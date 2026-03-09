<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('status');
            $table->string('barcode')->nullable()->unique()->after('tags');
            $table->string('priority')->default('normal')->after('barcode'); // normal, urgent, vip
            $table->boolean('is_damaged')->default(false)->after('priority');
            $table->text('damage_notes')->nullable()->after('is_damaged');
            $table->foreignId('assigned_staff_id')->nullable()->after('damage_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('estimated_ready_at')->nullable()->after('assigned_staff_id');
            $table->timestamp('picked_up_at')->nullable()->after('estimated_ready_at');
            $table->unsignedInteger('loyalty_points_earned')->default(0)->after('picked_up_at');
            $table->boolean('wallet_used')->default(false)->after('loyalty_points_earned');
            $table->decimal('wallet_amount_used', 10, 2)->default(0)->after('wallet_used');

            $table->index('barcode');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tags', 'barcode', 'priority', 'is_damaged', 'damage_notes',
                'assigned_staff_id', 'estimated_ready_at', 'picked_up_at',
                'loyalty_points_earned', 'wallet_used', 'wallet_amount_used']);
        });
    }
};
