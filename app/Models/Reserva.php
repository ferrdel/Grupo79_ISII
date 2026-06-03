<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    // Si tu tabla en la base de datos se llama "reservas" en plural (según tu DER), 
    // es una excelente práctica especificarlo explícitamente aquí:
    protected $table = 'reservas';

    // Tu clave primaria según el diagrama de clases es nro_reserva
    protected $primaryKey = 'nro_reserva';
}
