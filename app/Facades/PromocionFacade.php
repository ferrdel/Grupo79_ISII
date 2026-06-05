<?php

namespace App\Facades;

use App\Models\Promociones;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PromocionFacade
{
    /**
     * Oculta el subsistema complejo para la creación y activación segura de promociones
     */
    public function crearYActivar(array $datos): Promociones
    {
        // 1. Persistencia en la Base de Datos MySQL
        $promocion = Promociones::create($datos);

        // 2. Registro automático en el Subsistema de Auditoría/Seguridad
        Log::info("AUDITORÍA: El administrador activó una nueva promoción ID: {$promocion->id} - Nombre: {$promocion->nombre_promo}");

        // 3. Operación en el Subsistema de Rendimiento (Limpieza de Caché de vistas)
        Cache::forget('promociones_activas_home');

        return $promocion;
    }

    /**
     * Oculta la complejidad al eliminar y dar de baja una promoción
     */
    public function eliminarPromocion(int $id): bool
    {
        $promocion = Promociones::findOrFail($id);
        
        Log::warning("AUDITORÍA: Eliminando promoción ID: {$promocion->id}");
        
        $resultado = $promocion->delete();
        Cache::forget('promociones_activas_home');

        return $resultado;
    }
}