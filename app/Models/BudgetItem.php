<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    protected $fillable = [
        'budget_id',
        'user_id',
        'name',
        'planned_amount',
        'spent_amount',
        'category_type',
        'category',
        'description',
        'status'
    ];

    protected $casts = [
        'planned_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2'
    ];

    /**
     * Get the budget this item belongs to
     */
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Get the user who owns this budget item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate the percentage of budget item used
     */
    public function getUsagePercentageAttribute()
    {
        if ($this->planned_amount <= 0) {
            return 0;
        }

        return min(100, ($this->spent_amount / $this->planned_amount) * 100);
    }

    /**
     * Calculate remaining amount for this budget item
     */
    public function getRemainingAmountAttribute()
    {
        return $this->planned_amount - $this->spent_amount;
    }

    /**
     * Get budget item status based on spending
     */
    public function getCalculatedStatusAttribute()
    {
        if ($this->spent_amount >= $this->planned_amount) {
            return 'exceeded';
        } elseif ($this->spent_amount >= ($this->planned_amount * 0.8)) {
            return 'warning';
        } else {
            return 'on_track';
        }
    }

    /**
     * Load calculated attributes with the budget item data
     */
    public function loadCalculatedAttributes()
    {
        return array_merge($this->toArray(), [
            'usage_percentage' => $this->usage_percentage,
            'remaining_amount' => $this->remaining_amount,
            'calculated_status' => $this->calculated_status
        ]);
    }

    /**
     * Scope to filter by category type
     */
    public function scopeByCategoryType($query, $categoryType)
    {
        return $query->where('category_type', $categoryType);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by specific category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get items for a specific budget
     */
    public function scopeForBudget($query, $budgetId)
    {
        return $query->where('budget_id', $budgetId);
    }
}