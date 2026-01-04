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
            'type' => 'salary',
            'balance' => 5000.00,
        ]);

        IncomeSource::create([
            'user_id' => 1,
            'name' => 'Freelance Work',
            'type' => 'business',
            'balance' => 2000.00,
        ]);

        IncomeSource::create([
            'user_id' => 2,
            'name' => 'Investment Returns',
            'type' => 'investment',
            'balance' => 10000.00,
        ]);

        IncomeSource::create([
            'user_id' => 2,
            'name' => 'Part-time Job',
            'type' => 'salary',
            'balance' => 1500.00,
        ]);
    }
}
