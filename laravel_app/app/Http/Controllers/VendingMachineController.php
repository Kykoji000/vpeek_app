<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VendingMachine;

class VendingMachineController extends Controller
{
    public function index()
    {
        $machines = VendingMachine::with('inventories.product')->get();
        return view('vending_machines.index', compact('machines'));
    }

    public function show($id)
    {
        $machine = VendingMachine::with('inventories.product')->findOrFail($id);
        return view('vending_machines.show', compact('machine'));
    }

    public function nearby(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        return VendingMachine::selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
            * cos(radians(longitude) - radians(?)) 
            + sin(radians(?)) * sin(radians(latitude)))) AS distance",
            [$lat, $lng, $lat]
        )
        ->orderBy('distance')
        ->limit(5)
        ->get();
    }
}
