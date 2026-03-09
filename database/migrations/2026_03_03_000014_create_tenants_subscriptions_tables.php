<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->enum('plan', ['starter', 'professional', 'enterprise'])->default('starter');
            $table->enum('status', ['active', 'suspended', 'cancelled'])->default('active');
            $table->json('settings')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('plan');
            $table->string('status')->default('active'); // active, past_due, cancelled
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->string('payment_method')->nullable();
            $table->string('external_id')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        // Add tenant_id to key tables for multi-tenancy
        Schema::table('branches', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenants');
    }
};
