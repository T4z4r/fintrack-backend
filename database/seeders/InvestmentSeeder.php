<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Investment::create([
            'user_id' => 1,
            'name' => 'Stock Portfolio',
            'amount_invested' => 10000.00,
            'start_date' => '2023-06-01',
            'expected_return' => 15.00,
            'status' => 'active',
        ]);

        Investment::create([
            'user_id' => 1,
            'name' => 'Real Estate Fund',
            'amount_invested' => 5000.00,
            'start_date' => '2023-08-01',
            'expected_return' => 10.00,
            'status' => 'active',
        ]);

        Investment::create([
            'user_id' => 2,
            'name' => 'Crypto Investment',
            'amount_invested' => 2000.00,
            'start_date' => '2023-09-01',
            'expected_return' => 20.00,
            'status' => 'active',
        ]);

        Investment::create([
            'user_id' => 2,
            'name' => 'Bond Fund',
            'amount_invested' => 3000.00,
            'start_date' => '2023-07-01',
            'expected_return' => 8.00,
            'status' => 'closed',
        ]);
    }
}
