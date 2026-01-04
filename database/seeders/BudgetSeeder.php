<?php

namespace Database\Seeders;

use App\Models\Budget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Budget::create([
            'user_id' => 1,
            'name' => 'Monthly Expenses Budget',
            'planned_amount' => 3000.00,
            'spent_amount' => 250.00,
            'time_period' => 'monthly',
            'category_type' => 'expense',
            'status' => 'on_track',
            'description' => 'Budget for monthly expenses',
            'month' => 1,
            'year' => 2024,
        ]);

        Budget::create([
            'user_id' => 1,
            'name' => 'Annual Income Budget',
            'planned_amount' => 60000.00,
            'spent_amount' => 6500.00,
            'time_period' => 'yearly',
            'category_type' => 'income',
            'status' => 'on_track',
            'description' => 'Budget for annual income',
            'month' => null,
            'year' => 2024,
        ]);

        Budget::create([
            'user_id' => 2,
            'name' => 'Entertainment Budget',
            'planned_amount' => 500.00,
            'spent_amount' => 100.00,
            'time_period' => 'monthly',
            'category_type' => 'expense',
            'status' => 'on_track',
            'description' => 'Budget for entertainment',
            'month' => 1,
            'year' => 2024,
        ]);

        Budget::create([
            'user_id' => 2,
            'name' => 'Investment Budget',
            'planned_amount' => 10000.00,
            'spent_amount' => 5000.00,
            'time_period' => 'yearly',
            'category_type' => 'investment',
            'status' => 'on_track',
            'description' => 'Budget for investments',
            'month' => null,
            'year' => 2024,
        ]);
    }
}
