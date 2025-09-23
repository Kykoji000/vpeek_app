<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendingMachine;

class VendingMachineSeeder extends Seeder
{
    public function run(): void
    {
        $centerLat = 35.170915;
        $centerLng = 136.881537;

        for ($i = 1; $i <= 50; $i++) {
            VendingMachine::create([
                'name' => "自販機{$i}",
                'latitude' => $centerLat + (mt_rand(-100, 100) / 1000),
                'longitude' => $centerLng + (mt_rand(-100, 100) / 1000),
            ]);
        }
    }
}
