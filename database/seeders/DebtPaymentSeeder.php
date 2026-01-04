<?php

namespace Database\Seeders;

use App\Models\DebtPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DebtPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Payments for Car Loan (debt_id 2, partial)
        DebtPayment::create([
            'debt_id' => 2,
            'user_id' => 1,
            'amount' => 5000.00,
            'payment_date' => '2023-12-01',
            'payment_method' => 'Bank Transfer',
            'notes' => 'Monthly payment',
        ]);

        DebtPayment::create([
            'debt_id' => 2,
            'user_id' => 1,
            'amount' => 5000.00,
            'payment_date' => '2024-01-01',
            'payment_method' => 'Bank Transfer',
            'notes' => 'Monthly payment',
        ]);

        // Payments for Student Loan (debt_id 3, paid)
        DebtPayment::create([
            'debt_id' => 3,
            'user_id' => 2,
            'amount' => 2500.00,
            'payment_date' => '2023-10-01',
            'payment_method' => 'Check',
            'notes' => 'Quarterly payment',
        ]);

        DebtPayment::create([
            'debt_id' => 3,
            'user_id' => 2,
            'amount' => 2500.00,
            'payment_date' => '2023-11-01',
            'payment_method' => 'Check',
            'notes' => 'Quarterly payment',
        ]);

        DebtPayment::create([
            'debt_id' => 3,
            'user_id' => 2,
            'amount' => 2500.00,
            'payment_date' => '2023-12-01',
            'payment_method' => 'Check',
            'notes' => 'Quarterly payment',
        ]);

        DebtPayment::create([
            'debt_id' => 3,
            'user_id' => 2,
            'amount' => 2500.00,
            'payment_date' => '2024-01-01',
            'payment_method' => 'Check',
            'notes' => 'Quarterly payment',
        ]);
    }
}
