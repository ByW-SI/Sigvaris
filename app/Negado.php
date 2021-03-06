<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Negado extends Model
{
    protected $table = 'negados';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'paciente_id',
        'producto_id',
        'cantidad',
        'fecha',
        'fecha_entrega',
        'comentarios',
        'folio',
        'producto_entregado_id',
        'oficina_id',
    ];

    public function paciente()
    {
        return $this->belongsTo('App\Paciente', 'paciente_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function productoEntregado()
    {
        return $this->belongsTo(Producto::class, 'producto_entregado_id');
    }

    public function oficina()
    {
        return $this->belongsTo('App\Oficina');
    }
}
