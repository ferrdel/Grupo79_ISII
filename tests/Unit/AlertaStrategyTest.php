<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Strategies\AlertaPermisivaStrategy;
use App\Services\Strategies\AlertaEstrictaStrategy;

class AlertaStrategyTest extends TestCase
{
    /**
     * VERIFICACIÓN 1: Probar la Estrategia Permisiva.
     * Si todos los meses tienen 0 reservas, el algoritmo debe marcar los 12 meses en alerta.
     */
    public function test_estrategia_permisiva_marca_alertas_solo_en_meses_con_cero_reservas()
    {
        // 1. ESCENARIO (Arrange): Simulamos un año con datos vacíos (0 reservas en cada mes)
        $datosMensualesVacios = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
        $strategy = new AlertaPermisivaStrategy();

        // 2. ACCIÓN (Act): Ejecutamos el algoritmo encapsulado de la estrategia
        $resultadoObtenido = $strategy->calcularMesesCriticos($datosMensualesVacios);

        // 3. VERIFICACIÓN (Assert): Esperamos que devuelva un arreglo con los 12 meses (del 1 al 12)
        $this->assertCount(12, $resultadoObtenido);
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], $resultadoObtenido);
    }

    /**
     * VERIFICACIÓN 2: Probar el comportamiento de intercambio del algoritmo.
     * Si cambiamos los datos y un mes supera el umbral, la estrategia debe excluirlo.
     */
    public function test_estrategia_estricta_excluye_meses_que_superan_el_minimo_de_reservas()
    {
        // 1. ESCENARIO (Arrange): Simulamos que en Enero (1) y Febrero (2) tuviste 10 reservas (buena temporada)
        // Pero el resto del año se mantiene en 0.
        $datosMensualesMixtos = [
            1 => 10, 2 => 10, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 
            7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0
        ];
        $strategy = new AlertaEstrictaStrategy(); // Alerta si es menor a 5 reservas

        // 2. ACCIÓN (Act)
        $resultadoObtenido = $strategy->calcularMesesCriticos($datosMensualesMixtos);

        // 3. VERIFICACIÓN (Assert): Enero (1) y Febrero (2) NO deben estar en el listado de alertas
        $this->assertNotContains(1, $resultadoObtenido);
        $this->assertNotContains(2, $resultadoObtenido);
        
        // El conteo total de alertas debe bajar a 10 meses críticos
        $this->assertCount(10, $resultadoObtenido);
    }
}