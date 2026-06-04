<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
    // Si tu tabla en la base de datos se llama "reservas" en plural (según tu DER), 
    // es una excelente práctica especificarlo explícitamente aquí:
    protected $table = 'reservas';

    // Tu clave primaria según el diagrama de clases es nro_reserva
    protected $primaryKey = 'nro_reserva';

    protected $fillable = ['fecha_inicio', 'hora_inicio', 'fecha_devolucion', 'hora_devolucion', 'precio_total', 'estado', 'dni_cliente', 'nro_patente', 'id_promocion'];

    /**
     * Relación Muchos a Muchos con Accesorios incluyendo los atributos del DER
     */
    public function accesorios()
    {
        return $this->belongsToMany(Accesorios::class, 'reservas_accesorios', 'nro_reserva', 'id_accesorio')
                    ->withPivot('cantidad', 'precio_applied'); // Mapea los campos de tu tabla intermedia
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'dni_cliente', 'dni_cliente');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class, 'nro_patente', 'nro_patente');
    }

    public function promocion()
    {
        return $this->belongsTo(Promociones::class, 'id_promocion', 'id_promocion');
    }
}
