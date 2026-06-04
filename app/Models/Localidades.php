<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidades extends Model
{
    protected $table = 'localidades';
    protected $primaryKey = 'id_localidad';
    protected $fillable = ['nombre_localidad', 'id_provincia'];

    public function provincia()
    {
        return $this->belongsTo(Provincias::class, 'id_provincia', 'id_provincia');
    }
}
