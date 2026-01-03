<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'amount_invested',
        'start_date',
        'expected_return',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
