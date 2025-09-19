<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\VendingMachine;
use App\Models\Product;

class InventorySeeder extends Seeder
{
    public function run()
    {
        $machines = VendingMachine::all();
        $products = Product::all();

        foreach ($machines as $machine) {
            foreach ($products as $product) {
                Inventory::create([
                    'vending_machine_id' => $machine->id,
                    'product_id' => $product->id,
                    'stock' => rand(0, 20),
                ]);
            }
        }
    }
}
