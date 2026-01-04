<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Income::create([
            'user_id' => 1,
            'income_source_id' => 1,
            'category' => 'Salary',
            'amount' => 5000.00,
            'date' => '2024-01-01',
            'notes' => 'Monthly salary payment',
        ]);

        Income::create([
            'user_id' => 1,
            'income_source_id' => 2,
            'category' => 'Freelance',
            'amount' => 1500.00,
            'date' => '2024-01-15',
            'notes' => 'Web development project',
        ]);

        Income::create([
            'user_id' => 2,
            'income_source_id' => 3,
            'category' => 'Investment',
            'amount' => 2000.00,
            'date' => '2024-01-10',
            'notes' => 'Dividend payment',
        ]);

        Income::create([
            'user_id' => 2,
            'income_source_id' => 4,
            'category' => 'Salary',
            'amount' => 1500.00,
            'date' => '2024-01-01',
            'notes' => 'Part-time job payment',
        ]);
    }
}
