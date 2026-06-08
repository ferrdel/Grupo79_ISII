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
        //  EXTRAER EL MES CORRECTAMENTE:
        // Convertimos la "fecha_inicio" que viene del formulario a un objeto de Carbon para saber su mes
        // (Ajustá 'fecha_inicio' si tu input del HTML se llama diferente, por ejemplo 'fecha_desde')
        $mesSeleccionado = Carbon::parse($datos['fecha_inicio'])->month; 
        $anioSeleccionado = Carbon::parse($datos['fecha_inicio'])->year;

        // 2. BUSCAR SOLAPAMIENTOS EN ESE PERÍODO:
        // Verificamos si ya existe una promoción activa en el mismo mes y año
        $existe = Promociones::whereMonth('fecha_inicio', $mesSeleccionado)
                            ->whereYear('fecha_inicio', $anioSeleccionado)
                            ->where('estado', 'Activa')
                            ->exists();
        
        if ($existe) {
            // Acá personalizás el texto exacto que va a viajar a la pantalla
            throw new Exception("¡Ya existe una promoción para este mes !");
        }

        $datos['estado'] = 'Activa';

        //  Persistencia en la Base de Datos MySQL
        $promocion = Promociones::create($datos);

        //  Registro automático en el Subsistema de Auditoría/Seguridad
        Log::info("AUDITORÍA: El administrador activó una nueva promoción ID: {$promocion->id_promocion} - Nombre: {$promocion->nombre_promo}");

        // Operación en el Subsistema de Rendimiento (Limpieza de Caché de vistas)
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