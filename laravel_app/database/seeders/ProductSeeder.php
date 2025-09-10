<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create(['name' => 'コーラ', 'price' => 150]);
        Product::create(['name' => 'お茶', 'price' => 120]);
        Product::create(['name' => '水', 'price' => 100]);
        Product::create(['name' => 'レッドブル', 'price' => 250]);
    }
}
