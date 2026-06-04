<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculos extends Model
{
    use SoftDeletes;
    protected $table = 'vehiculos';
    protected $primaryKey = 'nro_patente';
    public $incrementing = false;
    protected $keyType = 'string';

    
    protected $fillable = [
        'nro_patente',
        'precio',
        'modelo',
        'anio',
        'marca',
        'imagen',
        'kilometros',
        'estado',
        'descripcion',
        'litros_combustible',
        'id_sucursal',
    ];

    // Relación inversa: Un vehículo pertenece a una sucursal
    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class, 'id_sucursal', 'id_sucursal');
    }

    /**
     * Regla de negocio analizada por la prueba unitaria.
     * Evalúa si la instancia actual tiene los requisitos mínimos obligatorios.
     */
    public function esValidoParaRegistro(array $patentesExistentes = []): bool
    {
        // 1. Validación de campos obligatorios vacíos (Casos CP_Ag1 y CP_Ag2)
        if (empty($this->nro_patente) || empty($this->marca) || empty($this->modelo) || 
            empty($this->anio) || empty($this->precio) || empty($this->estado)) {
            return false;
        }

        // 2. VALIDACIÓN DEL CASO CP_Ag5: Verificamos si la patente ya existe en el listado
        // in_array busca si el string de la patente actual está dentro del arreglo de exclusión
        if (in_array($this->nro_patente, $patentesExistentes)) {
            return false; // Rechaza el registro por estar duplicado
        }

        // Si supera todos los filtros, el vehículo es apto para registrarse
        return true;
    }
}