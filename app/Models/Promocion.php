<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones'; // Coherente con tu diagrama de clases

    protected $fillable = [
        'nombre_promo',
        'fecha_inicio',
        'fecha_fin',
        'descuento',
        'gps_gratis',
        'silla_bebe_descuento',
        'conductor_gratis',
        'estado'
    ];
}