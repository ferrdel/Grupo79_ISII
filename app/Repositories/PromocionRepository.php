<?php

namespace App\Repositories;

use App\Models\Promociones;
use Illuminate\Support\Facades\DB; // 💡 CLAVE: Importamos la fachada de BD nativa de Laravel

class PromocionRepository implements PromocionRepositoryInterface {
    
    // 1. Método que ya tenías (Alta vía Eloquent)
    public function guardar(array $datos): Promociones {
        return Promociones::create($datos);
    }

    // 2. Método que ya tenías (Baja vía Eloquent)
    public function eliminar(int $id): bool {
        $promo = Promociones::findOrFail($id);
        return $promo->delete();
    }

    // 🚀 NUEVO: Procedimiento Almacenado de CONSULTA para el Dashboard
    public function obtenerPromocionesCriticasDB(int $mes, int $anio): array {
        // Ejecutamos la rutina pasándole los parámetros de forma segura
        // Nota: Reemplazá 'id_promocion' en tu SP si tu columna se llama distinto
        return DB::select('CALL ObtenerPromocionesCriticas(?, ?)', [$mes, $anio]);
    }

    // 🚀 NUEVO: Procedimiento Almacenado de ACTUALIZACIÓN para el Descuento
    public function modificarDescuentoDB(int $id, float $nuevoDescuento): bool {
        // Ejecutamos el UPDATE directo en el motor de MySQL
        $filasAfectadas = DB::update('CALL ModificarDescuentoPromocion(?, ?)', [$id, $nuevoDescuento]);
        
        // Retorna verdadero si se modificó el registro físicamente
        return $filasAfectadas > 0;
    }
}