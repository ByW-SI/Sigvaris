<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'bancos';
    public $timestamps = false;
    
    protected $fillable=[
        'id',
        'nombre',
        'etiqueta'
    ];

}
