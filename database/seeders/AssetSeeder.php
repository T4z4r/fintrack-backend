<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asset::create([
            'user_id' => 1,
            'name' => 'Toyota Camry',
            'category' => 'Vehicle',
            'value' => 15000.00,
            'acquisition_date' => '2020-05-01',
        ]);

        Asset::create([
            'user_id' => 1,
            'name' => 'Gold Watch',
            'category' => 'Jewelry',
            'value' => 2000.00,
            'acquisition_date' => '2019-12-01',
        ]);

        Asset::create([
            'user_id' => 2,
            'name' => 'Apartment',
            'category' => 'Real Estate',
            'value' => 200000.00,
            'acquisition_date' => '2018-03-01',
        ]);

        Asset::create([
            'user_id' => 2,
            'name' => 'Laptop',
            'category' => 'Electronics',
            'value' => 1200.00,
            'acquisition_date' => '2022-01-01',
        ]);
    }
}
