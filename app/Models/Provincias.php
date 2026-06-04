<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincias extends Model
{
    protected $table = 'provincias';
    protected $primaryKey = 'id_provincia';
    protected $fillable = ['nombre_prov', 'ciudad'];

    public function localidades()
    {
        return $this->hasMany(Localidades::class, 'id_provincia', 'id_provincia');
    }
}
