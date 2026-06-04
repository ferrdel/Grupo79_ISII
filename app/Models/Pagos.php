<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    protected $fillable = ['fecha_pago', 'monto_pago', 'metodo_pago', 'estado', 'nro_reserva'];

    public function reserva()
    {
        return $this->belongsTo(Reservas::class, 'nro_reserva', 'nro_reserva');
    }
}
