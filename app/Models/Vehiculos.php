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
        'id_usuario',
    ];
}