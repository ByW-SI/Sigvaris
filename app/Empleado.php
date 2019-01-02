<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    protected $table = 'empleados';

    protected $fillable = ['id',
                           'nombre',
                           'appaterno',
                           'apmaterno',
                           'rfc',
                           'telefono',
                           'movil',
                           'email',
                           'nss',
                           'curp',
                           'infonavit',
                           'fnac',
                           'cp',
                           'calle',
                           'numext',
                           'numint',
                           'colonia',
                           'municipio',
                           'estado',
                           'calles',
                           'referencia'];
   

    protected $hidden=[
    	'created_at','updated_at'
    ];
    
    public function datosLaborales(){
        return $this->hasMany('App\EmpleadosDatosLab');
    }
    public function estudios(){
        return $this->hasOne('App\EmpleadosEstudios');
    }
    public function emergencias(){
        return $this->hasOne('App\EmpleadosEmergencias');
    }
    public function vacaciones(){
        return $this->hasMany('App\EmpleadosVacaciones');
    }
    public function faltasAdmin(){
        return $this->hasMany('App\EmpleadosFaltasAdministrativas');
    }
    
}
