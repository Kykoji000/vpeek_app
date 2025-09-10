<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // 自販機1に全商品ランダム在庫
        for ($i = 1; $i <= 4; $i++) {
            Inventory::create([
                'vending_machine_id' => 1,
                'product_id' => $i,
                'stock' => rand(0, 10),
            ]);
        }

        // 自販機2に全商品ランダム在庫
        for ($i = 1; $i <= 4; $i++) {
            Inventory::create([
                'vending_machine_id' => 2,
                'product_id' => $i,
                'stock' => rand(0, 10),
            ]);
        }

        // 自販機3に全商品ランダム在庫
        for ($i = 1; $i <= 4; $i++) {
            Inventory::create([
                'vending_machine_id' => 3,
                'product_id' => $i,
                'stock' => rand(0, 10),
            ]);
        }
    }
}
