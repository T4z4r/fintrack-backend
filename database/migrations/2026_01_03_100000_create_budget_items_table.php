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
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Budget item details
            $table->string('name');
            $table->decimal('planned_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->enum('category_type', ['income', 'investment', 'expense', 'asset', 'debt']);
            $table->string('category')->nullable(); // Specific category like 'groceries', 'rent', etc.
            $table->text('description')->nullable();

            // Status tracking
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');

            $table->timestamps();

            // Indexes for better query performance
            $table->index(['budget_id', 'user_id']);
            $table->index(['user_id', 'category_type']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};