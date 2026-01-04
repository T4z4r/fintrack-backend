<?php

namespace Database\Seeders;

use App\Models\BudgetItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BudgetItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Items for Monthly Expenses Budget (budget_id 1)
        BudgetItem::create([
            'budget_id' => 1,
            'user_id' => 1,
            'name' => 'Food',
            'planned_amount' => 500.00,
            'spent_amount' => 200.00,
            'category_type' => 'expense',
            'category' => 'Food',
            'description' => 'Groceries and dining out',
            'status' => 'on_track',
        ]);

        BudgetItem::create([
            'budget_id' => 1,
            'user_id' => 1,
            'name' => 'Transport',
            'planned_amount' => 300.00,
            'spent_amount' => 50.00,
            'category_type' => 'expense',
            'category' => 'Transport',
            'description' => 'Bus and taxi fares',
            'status' => 'on_track',
        ]);

        // Items for Annual Income Budget (budget_id 2)
        BudgetItem::create([
            'budget_id' => 2,
            'user_id' => 1,
            'name' => 'Salary',
            'planned_amount' => 50000.00,
            'spent_amount' => 5000.00,
            'category_type' => 'income',
            'category' => 'Salary',
            'description' => 'Primary salary income',
            'status' => 'on_track',
        ]);

        BudgetItem::create([
            'budget_id' => 2,
            'user_id' => 1,
            'name' => 'Freelance',
            'planned_amount' => 10000.00,
            'spent_amount' => 1500.00,
            'category_type' => 'income',
            'category' => 'Freelance',
            'description' => 'Freelance projects',
            'status' => 'active',
        ]);

        // Items for Entertainment Budget (budget_id 3)
        BudgetItem::create([
            'budget_id' => 3,
            'user_id' => 2,
            'name' => 'Movies',
            'planned_amount' => 200.00,
            'spent_amount' => 100.00,
            'category_type' => 'expense',
            'category' => 'Entertainment',
            'description' => 'Movie tickets and streaming',
            'status' => 'on_track',
        ]);

        // Items for Investment Budget (budget_id 4)
        BudgetItem::create([
            'budget_id' => 4,
            'user_id' => 2,
            'name' => 'Stocks',
            'planned_amount' => 5000.00,
            'spent_amount' => 2000.00,
            'category_type' => 'investment',
            'category' => 'Stocks',
            'description' => 'Stock investments',
            'status' => 'on_track',
        ]);

        BudgetItem::create([
            'budget_id' => 4,
            'user_id' => 2,
            'name' => 'Crypto',
            'planned_amount' => 3000.00,
            'spent_amount' => 2000.00,
            'category_type' => 'investment',
            'category' => 'Crypto',
            'description' => 'Cryptocurrency investments',
            'status' => 'on_track',
        ]);

        BudgetItem::create([
            'budget_id' => 4,
            'user_id' => 2,
            'name' => 'Bonds',
            'planned_amount' => 2000.00,
            'spent_amount' => 1000.00,
            'category_type' => 'investment',
            'category' => 'Bonds',
            'description' => 'Bond investments',
            'status' => 'on_track',
        ]);
    }
}
