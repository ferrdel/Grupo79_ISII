<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AuditoriaService implements AuditoriaServiceInterface {
    public function registrarAccion(string $accion, array $datos): void {
        Log::info("AUDITORÍA: " . $accion, $datos);
    }
}