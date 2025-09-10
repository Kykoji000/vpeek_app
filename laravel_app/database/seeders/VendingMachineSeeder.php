<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendingMachine;

class VendingMachineSeeder extends Seeder
{
    public function run()
    {
        VendingMachine::create(['name' => '駅前自販機', 'latitude' => 35.6895, 'longitude' => 139.6917]);
        VendingMachine::create(['name' => '公園前自販機', 'latitude' => 35.6890, 'longitude' => 139.6920]);
        VendingMachine::create(['name' => '学校前自販機', 'latitude' => 35.6885, 'longitude' => 139.6905]);
    }
}
