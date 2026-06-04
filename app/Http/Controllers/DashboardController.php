<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservas; // Asegúrate de que el modelo Reserva exista e interactúe con tu tabla
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Atributos de la clase encapsulados (como diseñamos en la estructura de la clase)
    private $fechaGeneracion;
    private $anioAnalisis;
    private $umbralCriticoBajaDemanda = 5; // Umbral metodológico: menos de 5 reservas es baja demanda

    public function __construct()
    {
        $this->fechaGeneracion = now();
    }

    /**
     * Orquestador principal del Dashboard
     */
    public function index(Request $request)
    {
        // Capturamos el año seleccionado del filtro (2026 por defecto)
        $anio = $request->input('anio', 2026);

        // 1. Conteo Total General (El que ya te marca 27)
        $totalReservas = Reservas::whereYear('fecha_inicio', $anio)->count();

        // 2. OBTENER RESERVAS AGRUPADAS POR MES (Revisa esta consulta)
        // Usamos 'fecha_inicio' que es el campo real de tu DER y tu lote SQL
        $reservasPorMes = Reservas::select(
                            DB::raw('MONTH(fecha_inicio) as mes'),
                            DB::raw('COUNT(*) as cantidad')
                        )
                        ->whereYear('fecha_inicio', $anio)
                        ->groupBy(DB::raw('MONTH(fecha_inicio)'))
                        ->pluck('cantidad', 'mes')
                        ->toArray();

        // Inicializamos los 12 meses en 0 para mapear el gráfico limpio
        $datosGrafico = array_fill(1, 12, 0);
        $mesesBajaDemanda = [];
        $umbralMinimo = 2; // Ejemplo: menos de 2 reservas es alerta crítica

        for ($m = 1; $m <= 12; $m++) {
            // Si el mes tiene reservas en la DB, se las asignamos
            if (isset($reservasPorMes[$m])) {
                $datosGrafico[$m] = $reservasPorMes[$m];
            }

            // Si las reservas de ese mes no superan el umbral, va a alerta
            if ($datosGrafico[$m] < $umbralMinimo) {
                $mesesBajaDemanda[] = $m;
            }
        }

        $mesesEnAlerta = count($mesesBajaDemanda);
        $nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        return view('admin.dashboard', [
            'totalReservas' => $totalReservas,
            'mesesEnAlerta' => $mesesEnAlerta,
            'mesesBajaDemanda' => $mesesBajaDemanda,
            'datosGrafico' => array_values($datosGrafico), // Pasa los datos limpios al JS [4, 3, 2, 2, 1...]
            'nombresMeses' => $nombresMeses,
            'anio' => $anio
        ]);
    }

    /**
     * OPERACIÓN: + obtenerDemandaPorPeriodo(anio)
     * Realiza una consulta agrupada por mes usando Eloquent sobre la tabla RESERVAS
     */
    private function obtenerDemandaPorPeriodo(int $anio): array
    {
        // Agrupamos y contamos las reservas de la base de datos para el año seleccionado
        $demanda = Reservas::select(
                DB::raw('MONTH(fecha_inicio) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('fecha_inicio', $anio)
            ->groupBy(DB::raw('MONTH(fecha_inicio)'))
            ->pluck('total', 'mes') // Crea un mapeo de clave => valor [mes => total]
            ->all();

        // Inicializamos y aseguramos que los 12 meses existan en el mapa (incluso con 0 reservas)
        $resultadoConstruido = [];
        for ($i = 1; $i <= 12; $i++) {
            $resultadoConstruido[$i] = $demanda[$i] ?? 0;
        }

        return $resultadoConstruido;
    }

    /**
     * OPERACIÓN: + identificarMesesCriticos(datosDemanda)
     * Evalúa qué meses no alcanzaron el umbral mínimo de ocupación
     */
    private function identificarMesesCriticos(array $datosDemanda): array
    {
        $mesesAlertados = [];
        
        foreach ($datosDemanda as $mes => $totalReservas) {
            if ($totalReservas < $this->umbralCriticoBajaDemanda) {
                $mesesAlertados[] = $mes; // Almacenamos el número del mes en estado crítico
            }
        }
        
        return $mesesAlertados;
    }
}