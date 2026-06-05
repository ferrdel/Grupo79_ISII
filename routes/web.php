<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromocionController;

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

    // Ruta existente de tu Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Rutas del módulo de Promociones
    Route::get('/promociones/create', [PromocionController::class, 'RegistrarPromocion'])->name('promociones.create');
    Route::post('/promociones', [PromocionController::class, 'store'])->name('promociones.store');

    // Rutas para Modificar
    Route::get('/promociones/{id}/edit', [PromocionController::class, 'edit'])->name('promociones.edit');
    Route::put('/promociones/{id}', [PromocionController::class, 'ModificarPromocion'])->name('promociones.update');
    
    // Ruta para Eliminar
    Route::delete('/promociones/{id}', [PromocionController::class, 'EliminarPromocion'])->name('promociones.destroy');
});