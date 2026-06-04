<?php

namespace App\Services\Strategies;

interface AlertaStrategyInterface
{
    /**
     * @param array $datosMensuales Arreglo con la cantidad de reservas por mes
     * @return array Listado de números de meses que entran en alerta crítica
     */
    public function calcularMesesCriticos(array $datosMensuales): array;
}