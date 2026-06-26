<?php

namespace App\Repositories;

use App\Models\Promociones;

interface PromocionRepositoryInterface {
    public function guardar(array $datos): Promociones;
    public function eliminar(int $id): bool;
    
    // 💡 Declaramos los dos nuevos contratos para los procedimientos
    public function obtenerPromocionesCriticasDB(int $mes, int $anio): array;
    public function modificarDescuentoDB(int $id, float $nuevoDescuento): bool;
}