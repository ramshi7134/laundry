<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add discount & notes columns to orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'discount_type')) {
                $table->string('discount_type')->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('orders', 'discount_reference')) {
                $table->string('discount_reference')->nullable()->after('discount_type');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('discount_reference');
            }
        });

        // Add reference & note columns to payments
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'reference')) {
                $table->string('reference')->nullable()->after('method');
            }
            if (!Schema::hasColumn('payments', 'note')) {
                $table->string('note')->nullable()->after('reference');
            }
        });

        // Add description & turnaround_hours to services
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('services', 'turnaround_hours')) {
                $table->unsignedInteger('turnaround_hours')->nullable()->after('price');
            }
            if (!Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('turnaround_hours');
            }
        });

        // Add is_active to branches
        Schema::table('branches', function (Blueprint $table) {
            if (!Schema::hasColumn('branches', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('phone');
            }
        });

        // Add quantity_before & quantity_after to inventory_transactions
        Schema::table('inventory_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_transactions', 'quantity_before')) {
                $table->decimal('quantity_before', 10, 3)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('inventory_transactions', 'quantity_after')) {
                $table->decimal('quantity_after', 10, 3)->nullable()->after('quantity_before');
            }
            if (!Schema::hasColumn('inventory_transactions', 'reason')) {
                $table->string('reason')->nullable()->after('quantity_after');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'discount_type', 'discount_reference', 'notes']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['reference', 'note']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['description', 'turnaround_hours', 'is_active']);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn(['quantity_before', 'quantity_after', 'reason']);
        });
    }
};
