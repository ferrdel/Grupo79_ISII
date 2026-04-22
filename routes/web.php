<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehiculoController;

// 1. Redirigir la raíz directamente al panel de administración
Route::get('/', function () {
    return redirect()->route('vehiculos.index');
});

// 2. Mantener tu grupo de rutas admin
Route::prefix('admin')->group(function () {
    Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
    Route::post('/vehiculos', [VehiculoController::class, 'RegistrarVehiculo'])->name('vehiculos.RegistrarVehiculo');
    Route::put('/vehiculos/{nro_patente}', [VehiculoController::class, 'ActualizarVehiculo'])->name('vehiculos.ActualizarVehiculo');
    Route::delete('/vehiculos/{nro_patente}', [VehiculoController::class, 'EliminarVehiculo'])->name('vehiculos.EliminarVehiculo');
});