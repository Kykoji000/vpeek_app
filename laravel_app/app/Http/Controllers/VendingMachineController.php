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
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if (!$lat || !$lng) {
            // 使いやすく、エラー時は空リストを返すかメッセージ表示にしても良い
            return view('vending_machines.nearby', [
                'machines' => collect(),
                'lat' => $lat,
                'lng' => $lng
            ]);
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
        ->take(5)
        ->get();

        return view('vending_machines.nearby', compact('machines', 'lat', 'lng'));
    }
    public function nearbyApi(Request $request)
    {
        // dd($request->input());
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
