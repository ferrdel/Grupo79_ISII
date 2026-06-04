<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accesorios extends Model
{
    protected $table = 'accesorios';
    protected $primaryKey = 'id_accesorio';
    protected $fillable = ['nombre_accesorio', 'precio_base', 'estado'];
}
