<?php

namespace App\Services\Strategies;

class AlertaPermisivaStrategy implements AlertaStrategyInterface
{
    public function calcularMesesCriticos(array $datosMensuales): array
    {
        $criticos = [];
        foreach ($datosMensuales as $mes => $cantidadReservas) {
            if ($cantidadReservas == 0) { // Criterio permisivo (tu estado actual con la BD vacía)
                $criticos[] = $mes;
            }
        }
        return $criticos;
    }
}