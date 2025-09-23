<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendingMachineController;

Route::get('/', function () { return view('welcome'); });

Route::get('/map', [VendingMachineController::class, 'mapView'])->name('map');
