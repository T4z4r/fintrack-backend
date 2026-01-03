<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'planned_amount',
        'spent_amount',
        'time_period',
        'category_type',
        'status',
        'description',
        // Keep existing fields for backward compatibility
        'category',
        'monthly_limit',
        'month',
        'year'
    ];

    protected $casts = [
        'planned_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'monthly_limit' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all budget items for this budget
     */
    public function budgetItems()
    {
        return $this->hasMany(BudgetItem::class);
    }

    /**
     * Calculate total planned amount from budget items
     */
    public function getTotalPlannedAmountAttribute()
    {
        return $this->budgetItems()->sum('planned_amount');
    }

    /**
     * Calculate total spent amount from budget items
     */
    public function getTotalSpentAmountAttribute()
    {
        return $this->budgetItems()->sum('spent_amount');
    }

    /**
     * Calculate the percentage of budget used.
     */
    public function getUsagePercentageAttribute()
    {
        $totalPlanned = $this->total_planned_amount;
        if ($totalPlanned <= 0) {
            return 0;
        }

        return min(100, ($this->total_spent_amount / $totalPlanned) * 100);
    }

    /**
     * Calculate remaining amount.
     */
    public function getRemainingAmountAttribute()
    {
        return $this->total_planned_amount - $this->total_spent_amount;
    }

    /**
     * Get budget status based on spending.
     */
    public function getCalculatedStatusAttribute()
    {
        $totalSpent = $this->total_spent_amount;
        $totalPlanned = $this->total_planned_amount;

        if ($totalSpent >= $totalPlanned) {
            return 'exceeded';
        } elseif ($totalSpent >= ($totalPlanned * 0.8)) {
            return 'warning';
        } else {
            return 'on_track';
        }
    }

    /**
     * Get budget items grouped by category type
     */
    public function getItemsByCategoryTypeAttribute()
    {
        return $this->budgetItems->groupBy('category_type');
    }

    /**
     * Load calculated attributes with the budget data.
     */
    public function loadCalculatedAttributes()
    {
        return array_merge($this->toArray(), [
            'total_planned_amount' => $this->total_planned_amount,
            'total_spent_amount' => $this->total_spent_amount,
            'usage_percentage' => $this->usage_percentage,
            'remaining_amount' => $this->remaining_amount,
            'calculated_status' => $this->calculated_status,
            'budget_items_count' => $this->budgetItems()->count()
        ]);
    }

    /**
     * Load budget with items and their calculated attributes
     */
    public function loadWithItems()
    {
        $this->load('budgetItems');
        $budgetData = $this->loadCalculatedAttributes();

        $budgetData['budget_items'] = $this->budgetItems->map(function ($item) {
            return $item->loadCalculatedAttributes();
        });

        return $budgetData;
    }

    /**
     * Scope to filter by time period.
     */
    public function scopeByTimePeriod($query, $timePeriod)
    {
        return $query->where('time_period', $timePeriod);
    }

    /**
     * Scope to filter by category type.
     */
    public function scopeByCategoryType($query, $categoryType)
    {
        return $query->where('category_type', $categoryType);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get budgets with their items
     */
    public function scopeWithItems($query)
    {
        return $query->with('budgetItems');
    }
}
