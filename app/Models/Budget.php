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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate the percentage of budget used.
     */
    public function getUsagePercentageAttribute()
    {
        if ($this->planned_amount <= 0) {
            return 0;
        }
        
        return min(100, ($this->spent_amount / $this->planned_amount) * 100);
    }

    /**
     * Calculate remaining amount.
     */
    public function getRemainingAmountAttribute()
    {
        return $this->planned_amount - $this->spent_amount;
    }

    /**
     * Get budget status based on spending.
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
     * Load calculated attributes with the budget data.
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
}
