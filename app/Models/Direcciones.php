<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direcciones extends Model
{
    protected $table = 'direcciones';
    protected $primaryKey = 'id_direccion';
    protected $fillable = ['calle', 'altura', 'departamento', 'id_localidad'];

    public function localidad()
    {
        return $this->belongsTo(Localidades::class, 'id_localidad', 'id_localidad');
    }
}
