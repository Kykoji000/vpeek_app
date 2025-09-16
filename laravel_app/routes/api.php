<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendingMachineController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/vending-machines/nearby', [VendingMachineController::class, 'nearbyApi']);
