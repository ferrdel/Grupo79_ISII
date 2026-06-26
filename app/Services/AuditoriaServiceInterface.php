<?php

namespace App\Services;

interface AuditoriaServiceInterface {
    public function registrarAccion(string $accion, array $datos): void;
}