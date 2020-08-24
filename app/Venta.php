<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'paciente_id',
        'fecha',
        'subtotal',
        'descuento_id',
        'total',
        'promocion_id',
        'sigpesos',
        'created_at',
        'line',
        'upc',
        'swiss_id',
        'empleado_id',
        'tipoPago',
        'banco',
        'digitos_targeta',
        'PagoTarjeta',
        'PagoEfectivo',
        'mesesPago',
        'requiere_factura',
        'comentario',
        'oficina_id',
        'cumpleDes'
    ];

    /**
     * =============
     * RELATIONSHIPS
     * =============
     */

    public function historialCambios()
    {
        return $this->hasMany('App\HistorialCambioVenta');
    }

    public function productos()
    {
        return $this->belongsToMany('App\Producto', 'producto_venta')->withPivot('cantidad', 'precio');
    }

    public function paciente()
    {
        return $this->belongsTo('App\Paciente', 'paciente_id');
    }

    public function descuento()
    {
        return $this->belongsTo('App\Descuento', 'descuento_id');
    }
    public function oficina()
    {
        return $this->belongsTo('App\Oficina');
    }

    public function factura()
    {
        return $this->hasOne('App\Factura');
    }

    public function promocion()
    {
        return $this->belongsTo('App\Promocion', 'promocion_id');
    }
    public function empleado()
    {
        return $this->belongsTo('App\Empleado', 'empleado_id');
    }

    public function SigpesosVenta()
    {
        return $this->hasMany('App\Sigpesosventa');
    }
    public function debolucion()
    {
        return $this->hasMany('App\Devolucion');
    }

     public function producto_damage()
    {
        return $this->belongsTo('App\ProductoDamage', 'productos_damage');
    }

    /**
     * ==========
     * ATTRIBUTES
     * ==========
     */

    public function getCantidadProductosAttribute()
    {
        return $this->productos->pluck('pivot')->flatten()->pluck('cantidad')->flatten()->sum();
    }

    
}
