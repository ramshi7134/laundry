<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('license_key')->unique();
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
            $table->enum('status', ['active', 'expired', 'revoked'])->default('active');
            $table->string('machine_id')->nullable(); // Optional: bind license to a specific device
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
