<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            IncomeSourceSeeder::class,
            IncomeSeeder::class,
            ExpenseSeeder::class,
            InvestmentSeeder::class,
            DebtSeeder::class,
            AssetSeeder::class,
            BudgetSeeder::class,
            DebtPaymentSeeder::class,
            BudgetItemSeeder::class,
        ]);
    }
}
