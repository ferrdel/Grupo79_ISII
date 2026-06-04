<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'dni_cliente';
    public $incrementing = false; // No es incremental autoincrementable por la DB
    protected $fillable = ['dni_cliente', 'nombre', 'apellido', 'telefono', 'email', 'nro_licencia', 'id_direccion'];

    public function direccion()
    {
        return $this->belongsTo(Direcciones::class, 'id_direccion', 'id_direccion');
    }
}
