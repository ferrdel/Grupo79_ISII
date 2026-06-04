<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
    protected $table = 'sucursales';
    protected $primaryKey = 'id_sucursal';
    protected $fillable = ['nombre_suc', 'hra_atencion', 'id_direccion'];

    public function direccion()
    {
        return $this->belongsTo(Direcciones::class, 'id_direccion', 'id_direccion');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculos::class, 'id_sucursal', 'id_sucursal');
    }
}
