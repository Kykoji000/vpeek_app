<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendingMachineController;

Route::get('/', function () {return view('welcome');});
Route::get('/vending-machines', [VendingMachineController::class, 'index']);
Route::get('/vending-machines/{id}', [VendingMachineController::class, 'show']);
Route::get('/nearby-vending-machines', [VendingMachineController::class, 'nearby']);