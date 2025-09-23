<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendingMachineController;

Route::get('/vending-machines', [VendingMachineController::class, 'all']);
Route::get('/vending-machines/{id}', [VendingMachineController::class, 'showApi']);
Route::get('/vending-machines/nearby', [VendingMachineController::class, 'nearbyApi']); // optional
