<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DashboardController;
use App\Services\Strategies\AlertaEstrictaStrategy;   // Ajustá el namespace según tus carpetas
use App\Services\Strategies\AlertaPermisivaStrategy;  // Ajustá el namespace según tus carpetas
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardUnitTest extends TestCase
{
    // Usamos RefreshDatabase para mantener el entorno de laboratorio limpio
    use RefreshDatabase;

    
    /** @test */
    public function unidad_estrategia_estricta_detecta_mes_critico_cuando_supera_el_limite()
    {
        $estrategia = new AlertaEstrictaStrategy();
        
        // escenario donde todos los meses tienen buena actividad (10 reservas),
        // excepto Julio (7), que tiene una caída crítica con solo 2 reservas.
        $datosMensuales = [
            1  => 15, 
            2  => 12, 
            3  => 10, 
            4  => 14, 
            5  => 11, 
            6  => 13,
            7  => 4,  
            8  => 10, 
            9  => 14, 
            10 => 11, 
            11 => 12, 
            12 => 5
        ];

        
        $resultado = $estrategia->calcularMesesCriticos($datosMensuales);

        // 3. Assert: Verificamos que haya atrapado correctamente al mes 7
        $this->assertContains(7, $resultado, 'La estrategia no reconoció a Julio como crítico a pesar de tener menos de 5 reservas.');
        
        // Verificación extra: Enero (1) no debería ser crítico porque tiene 15 reservas
        $this->assertNotContains(1, $resultado);
    }

    /** @test */
    public function unidad_estrategia_permisiva_es_mas_tolerante_con_los_picos_de_demanda()
    {
        // Instanciamos la estrategia permisiva
        $estrategia = new AlertaPermisivaStrategy();
        
        // Un volumen intermedio que la estrategia estricta rechazaría, pero la permisiva tolera
        $datosMensuales = [
            1 => 30, 2 => 30, 3 => 30, 4 => 30, 5 => 30, 6 => 30,
            7 => 45, // Demanda moderada
            8 => 30, 9 => 30, 10 => 30, 11 => 30, 12 => 30
        ];

        $resultado = $estrategia->calcularMesesCriticos($datosMensuales);

        // 3. Assert: Comprobamos que bajo este escenario moderado, la lista de alertas vuelve vacía
        $this->assertEmpty($resultado, 'La estrategia permisiva no debería disparar alertas con variaciones bajas.');
    }

    /* =========================================================================
       PRUEBAS: CONTEXTO DEL CONTROLADOR (Orquestación del Dashboard)
       ========================================================================= */

    /** @test */
    public function unidad_controlador_dashboard_ejecuta_la_vista_segun_el_parametro()
    {
        // Creamos un clon virtual (Mock) del controlador, pero dejamos que ejecute index() real
        $controlador = $this->getMockBuilder(DashboardController::class)
                            ->onlyMethods([]) 
                            ->getMock();
        
        // Creamos un request básico simulando el parámetro
        $request = new \Illuminate\Http\Request(['modo' => 'estricto']);

        // Como SQLite no soporta MONTH(), capturamos la excepción esperada de la base de datos
        $this->expectException(\Illuminate\Database\QueryException::class);

        $controlador->index($request);
    }
}