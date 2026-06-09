<?php

namespace App\Facades;

use Carbon\Carbon;
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
        // 1. Convertimos a Carbon una sola vez
        $inicio = Carbon::parse($datos['fecha_inicio']);
        $fin = Carbon::parse($datos['fecha_fin']);

        // 2. PRIMER ESCUDO (Local): Validar consistencia cronológica
        if ($inicio->greaterThan($fin)) {
            throw new \Exception("La fecha inicio no puede ser mayor a la fecha fin");
        }

        // 3. SEGUNDO ESCUDO (Local): Control de período (Mismo mes y año)
        if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
            throw new \Exception("¡Control de Período: Las fechas deben corresponder estrictamente al mismo mes y año!");
        }

        // 4. TERCER ESCUDO (Base de Datos): Buscar solapamientos usando las variables existentes
        $existe = Promociones::whereMonth('fecha_inicio', $inicio->month)
                            ->whereYear('fecha_inicio', $inicio->year)
                            ->where('estado', 'Activa')
                            ->exists();
        
        if ($existe) {
            throw new \Exception("¡Ya existe una promoción para este mes !");
        }

        // 5. Preparación e Inserción
        $datos['estado'] = 'Activa';
        $promocion = Promociones::create($datos);

        // 6. Subsistema de Auditoría
        Log::info("AUDITORÍA: El administrador activó una nueva promoción ID: {$promocion->id_promocion} - Nombre: {$promocion->nombre_promo}");

        // 7. Subsistema de Rendimiento
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