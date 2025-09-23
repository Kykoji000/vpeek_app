<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendingMachine;

class VendingMachineController extends Controller
{
    public function mapView()
    {
        return view('map');
    }

    public function all()
    {
        return response()->json(VendingMachine::with('inventories.product')->get());
    }

    public function showApi($id)
    {
        $machine = VendingMachine::with('inventories.product')->findOrFail($id);

        return response()->json([
            'id' => $machine->id,
            'name' => $machine->name,
            'latitude' => (float)$machine->latitude,
            'longitude' => (float)$machine->longitude,
            'inventories' => $machine->inventories->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'stock' => $inv->stock,
                    'product' => [
                        'id' => $inv->product->id,
                        'name' => $inv->product->name,
                        'price' => $inv->product->price,
                    ],
                ];
            })->values(),
        ]);
    }

    public function nearbyApi(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if (!$lat || !$lng) {
            return response()->json(['error' => 'lat and lng are required'], 400);
        }

        $machines = VendingMachine::selectRaw(
            "vending_machines.*, 
             (6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude))
             )) AS distance",
            [$lat, $lng, $lat]
        )
        ->orderBy('distance')
        ->with('inventories.product')
        ->take(50)
        ->get();

        return response()->json($machines);
    }
}
