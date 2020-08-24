<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoDamage extends Model
{
    protected $table = "productos_damage";
    protected $fillable = ["producto_id", "tipo_damage", "user_id", "descripcion",'created_at','id','origin_id','destinate_id'];

    public function producto(){
        return $this->belongsTo('App\Producto');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function venta(){
        return $this->belongsTo('App\Venta');
    }

}
