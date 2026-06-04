<?php

namespace App\Services\Strategies;

class AlertaEstrictaStrategy implements AlertaStrategyInterface
{
    public function calcularMesesCriticos(array $datosMensuales): array
    {
        $criticos = [];
        foreach ($datosMensuales as $mes => $cantidadReservas) {
            if ($cantidadReservas < 5) { // Criterio estricto
                $criticos[] = $mes;
            }
        }
        return $criticos;
    }
}