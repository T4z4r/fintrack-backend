<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = ['user_id', 'name', 'amount', 'due_date', 'status'];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all payments for this debt
     */
    public function payments()
    {
        return $this->hasMany(DebtPayment::class);
    }

    /**
     * Get total amount paid for this debt
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Get remaining balance for this debt
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->amount - $this->total_paid;
    }

    /**
     * Get payment status based on payments
     */
    public function getCalculatedStatusAttribute()
    {
        if ($this->remaining_balance <= 0) {
            return 'paid';
        } elseif ($this->total_paid > 0) {
            return 'partial';
        } else {
            return 'unpaid';
        }
    }

    /**
     * Scope to get debts by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get overdue debts
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
                    ->where('status', '!=', 'paid');
    }
}
