<?php

namespace App\Facades;

use Carbon\Carbon;
use App\Models\Promociones;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Repositories\PromocionRepository;
use App\Services\AuditoriaService;

class PromocionFacade
{
    private $promocionRepository;
    private $auditoriaService;

    public function __construct() {
        $this->promocionRepository = new PromocionRepository();
        $this->auditoriaService = new AuditoriaService();
    }

    
    public function crearYActivar(array $datos): Promociones
    {
        // 💡 1. Convertimos a Carbon una sola vez para validar consistencia cronológica
        $inicio = Carbon::parse($datos['fecha_inicio']);
        $fin = Carbon::parse($datos['fecha_fin']);

        // PRIMER ESCUDO: Validar consistencia cronológica
        if ($inicio->greaterThan($fin)) {
            throw new \Exception("La fecha inicio no puede ser mayor a la fecha fin");
        }

        // SEGUNDO ESCUDO: Control de período (Mismo mes y año)
        if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
            throw new \Exception("¡Control de Período: Las fechas deben corresponder estrictamente al mismo mes y año!");
        }

        // TERCER ESCUDO: Buscar solapamientos en la base de datos (Mes Duplicado)
        $existe = Promociones::whereMonth('fecha_inicio', $inicio->month)
                            ->whereYear('fecha_inicio', $inicio->year)
                            ->where('estado', 'Activa')
                            ->exists();
        
        if ($existe) {
            throw new \Exception("¡Ya existe una promoción para este mes !");
        }

        $datos['estado'] = 'Activa';

        // CAMBIO 1: Delegamos la persistencia al Repositorio según tu diagrama
        $promocion = $this->promocionRepository->guardar($datos);

        // CAMBIO 2: Delegamos el Log al Servicio de Auditoría según tu diagrama
        $this->auditoriaService->registrarAccion(
            "El administrador activó una nueva promoción ID: {$promocion->id} - Nombre: {$promocion->nombre_promo}",
            $datos
        );

        // Operación en el Subsistema de Rendimiento
        Cache::forget('promociones_activas_home');

        return $promocion;
    }

    /**
     * Oculta la complejidad al eliminar y dar de baja una promoción
     */
    public function eliminarPromocion(int $id): bool
    {
        // Registramos la acción en el servicio de seguridad corporativa
        $this->auditoriaService->registrarAccion("Eliminando promoción ID: {$id}", ['id' => $id]);

        // Delegamos la remoción física/lógica al Repositorio
        $resultado = $this->promocionRepository->eliminar($id);

        Cache::forget('promociones_activas_home');
        
        return $resultado;
    }

    
    public function modificarPromocion(int $id, array $datos): bool
    {
        // 1. Convertimos a Carbon una sola vez para las reglas de negocio
        $inicio = Carbon::parse($datos['fecha_inicio']);
        $fin = Carbon::parse($datos['fecha_fin']);

        // PRIMER ESCUDO: Validar consistencia cronológica
        if ($inicio->greaterThan($fin)) {
            throw new \Exception("La fecha inicio no puede ser mayor a la fecha fin");
        }

        // SEGUNDO ESCUDO: Control de período (Mismo mes y año)
        if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
            throw new \Exception("¡Control de Período: Las fechas deben corresponder estrictamente al mismo mes y año!");
        }

        // TERCER ESCUDO: Buscar solapamientos en la base de datos (Excluyendo la promoción actual)
        $existe = Promociones::whereMonth('fecha_inicio', $inicio->month)
                            ->whereYear('fecha_inicio', $inicio->year)
                            ->where('estado', 'Activa')
                            ->where('id', '!=', $id) // 💡 CLAVE: Ignora la promo que estamos editando
                            ->exists();
        
        if ($existe) {
            throw new \Exception("¡Ya existe una promoción para este mes !");
        }

        $nuevoDescuento = $datos['descuento'] > 1 ? $datos['descuento'] / 100 : $datos['descuento'];

        //Invocamos el procedimiento almacenado a través del Repositorio
        $resultado = $this->promocionRepository->modificarDescuentoDB($id, $nuevoDescuento);

        // Registramos la acción en el Subsistema de Auditoría
        $this->auditoriaService->registrarAccion(
            "El administrador modificó el descuento de la promoción ID: {$id} a " . ($nuevoDescuento * 100) . "%",
            $datos
        );
        // SUBSISTEMA DE RENDIMIENTO: Limpieza automática de Caché de vistas
        Cache::forget('promociones_activas_home');

        return $resultado;
    }

    public function consultarCampanasMensuales(int $mes, int $anio)
    {
        // Ejecuta el procedimiento pasando los parámetros de forma segura (PreparedStatement)
        $resultado = DB::select('CALL ObtenerPromocionesCriticas(?, ?)', [$mes, $anio]);
        
        return $resultado; 
    }

    public function actualizarDescuentoPorProcedimiento(int $id, float $nuevoDescuento): bool
    {
        // Ejecuta el procedimiento de actualización y devuelve las filas afectadas
        $filasAfectadas = DB::update('CALL ModificarDescuentoPromocion(?, ?)', [$id, $nuevoDescuento]);
        
        // Si afectó a una o más filas, la operación fue exitosa
        return $filasAfectadas > 0;
    }

}