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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Enhanced budget fields
            $table->string('name');
            $table->decimal('planned_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->enum('time_period', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->enum('category_type', ['income', 'investment', 'expense', 'asset', 'debt']);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('description')->nullable();
            
            // Backward compatibility fields
            $table->string('category')->nullable();
            $table->decimal('monthly_limit', 15, 2)->nullable();
            $table->integer('month')->nullable(); // 1-12
            $table->integer('year')->nullable();
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['user_id', 'time_period']);
            $table->index(['user_id', 'category_type']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
