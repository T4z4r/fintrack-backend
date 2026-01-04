<?php

namespace Database\Seeders;

use App\Models\IncomeSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IncomeSource::create([
            'user_id' => 1,
            'name' => 'Primary Salary',
            'type' => 'bank',
            'balance' => 5000.00,
        ]);

        IncomeSource::create([
            'user_id' => 1,
            'name' => 'Freelance Work',
            'type' => 'cash',
            'balance' => 2000.00,
        ]);

        IncomeSource::create([
            'user_id' => 2,
            'name' => 'Investment Returns',
            'type' => 'bank',
            'balance' => 10000.00,
        ]);

        IncomeSource::create([
            'user_id' => 2,
            'name' => 'Part-time Job',
            'type' => 'mno',
            'balance' => 1500.00,
        ]);
    }
}
