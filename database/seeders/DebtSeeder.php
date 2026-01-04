<?php

namespace Database\Seeders;

use App\Models\Debt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DebtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Debt::create([
            'user_id' => 1,
            'name' => 'Credit Card Debt',
            'amount' => 2000.00,
            'due_date' => '2024-06-01',
            'status' => 'unpaid',
        ]);

        Debt::create([
            'user_id' => 1,
            'name' => 'Car Loan',
            'amount' => 15000.00,
            'due_date' => '2025-12-01',
            'status' => 'partial',
        ]);

        Debt::create([
            'user_id' => 2,
            'name' => 'Student Loan',
            'amount' => 10000.00,
            'due_date' => '2024-03-01',
            'status' => 'paid',
        ]);

        Debt::create([
            'user_id' => 2,
            'name' => 'Personal Loan',
            'amount' => 5000.00,
            'due_date' => '2024-08-01',
            'status' => 'unpaid',
        ]);
    }
}
