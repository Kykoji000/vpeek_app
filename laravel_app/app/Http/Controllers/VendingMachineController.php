<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendingMachine;

class VendingMachineController extends Controller
{
    public function index()
    {
        return VendingMachine::with('inventories.product')->get();
    }

    public function show($id)
    {
        return VendingMachine::with('inventories.product')->findOrFail($id);
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
