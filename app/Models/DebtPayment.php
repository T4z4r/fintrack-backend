<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    protected $fillable = [
        'debt_id',
        'user_id', 
        'amount',
        'payment_date',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the debt this payment belongs to
     */
    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }

    /**
     * Get the user who made this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the total payments made for a specific debt
     */
    public static function getTotalPaymentsForDebt($debtId)
    {
        return self::where('debt_id', $debtId)->sum('amount');
    }

    /**
     * Get remaining balance for a debt
     */
    public static function getRemainingBalance($debtId)
    {
        $debt = Debt::find($debtId);
        $totalPayments = self::getTotalPaymentsForDebt($debtId);
        
        return $debt ? $debt->amount - $totalPayments : 0;
    }
}