<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('category'); // rent, salary, supplies, utilities, maintenance, other
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->date('date');
            $table->foreignId('created_by')->constrained('users');
            $table->string('receipt_photo')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'date']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
