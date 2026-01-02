<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{

    protected $fillable = [
        'user_id',
        'income_source_id',
        'category',
        'amount',
        'date',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function source()
    {
        return $this->belongsTo(IncomeSource::class, 'income_source_id');
    }
}
