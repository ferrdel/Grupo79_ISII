<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reserva; // Asegúrate de que el modelo Reserva exista e interactúe con tu tabla
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
        // Capturamos el año del filtro o por defecto el año actual
        $this->anioAnalisis = $request->input('anio', date('Y'));

        // 1. Ejecutamos la operación: obtenerDemandaPorPeriodo
        $datosDemanda = $this->obtenerDemandaPorPeriodo($this->anioAnalisis);

        // 2. Ejecutamos la operación: identificarMesesCriticos
        $mesesCriticos = $this->identificarMesesCriticos($datosDemanda);

        // Nombres abreviados de los meses para las etiquetas de Chart.js
        $nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Retornamos la vista inyectando las 4 variables que el frontend necesita
        return view('admin.dashboard', [
            'anio' => $this->anioAnalisis,
            'reporteFinal' => array_values($datosDemanda), // Devuelve solo los totales [10, 2, 4...]
            'mesesBajaDemanda' => $mesesCriticos,
            'nombresMeses' => $nombresMeses
        ]);
    }

    /**
     * OPERACIÓN: + obtenerDemandaPorPeriodo(anio)
     * Realiza una consulta agrupada por mes usando Eloquent sobre la tabla RESERVAS
     */
    private function obtenerDemandaPorPeriodo(int $anio): array
    {
        // Agrupamos y contamos las reservas de la base de datos para el año seleccionado
        $demanda = Reserva::select(
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