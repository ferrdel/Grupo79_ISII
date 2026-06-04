<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promociones extends Model
{
    protected $table = 'promociones'; 
    protected $primaryKey = 'id_promocion';

    protected $fillable = [
        'nombre_promo',
        'fecha_inicio',
        'fecha_fin',
        'descuento',
        'estado'
    ];
}