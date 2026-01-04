<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Expense::create([
            'user_id' => 1,
            'category' => 'Food',
            'amount' => 200.00,
            'payment_source' => 'Credit Card',
            'date' => '2024-01-05',
            'notes' => 'Grocery shopping',
        ]);

        Expense::create([
            'user_id' => 1,
            'category' => 'Transport',
            'amount' => 50.00,
            'payment_source' => 'Cash',
            'date' => '2024-01-10',
            'notes' => 'Bus fare',
        ]);

        Expense::create([
            'user_id' => 2,
            'category' => 'Entertainment',
            'amount' => 100.00,
            'payment_source' => 'Debit Card',
            'date' => '2024-01-12',
            'notes' => 'Movie tickets',
        ]);

        Expense::create([
            'user_id' => 2,
            'category' => 'Utilities',
            'amount' => 150.00,
            'payment_source' => 'Bank Transfer',
            'date' => '2024-01-08',
            'notes' => 'Electricity bill',
        ]);
    }
}
