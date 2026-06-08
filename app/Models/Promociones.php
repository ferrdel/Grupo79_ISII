<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promociones extends Model
{
    use SoftDeletes;
    protected $table = 'promociones'; 
    protected $primaryKey = 'id_promocion';

    protected $fillable = [
        'nombre_promo',
        'fecha_inicio',
        'fecha_fin',
        'descuento',
        'estado',
    ];
}