<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosdatoslabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()  
    {
        Schema::create('empleadosdatoslab', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empleado_id')->unsigned();
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->integer('contrato_id')->unsigned()->nullable();
            $table->foreign('contrato_id')->references('id')->on('tipocontrato');
            $table->integer('area_id')->unsigned()->nullable();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->integer('puesto_id')->unsigned()->nullable();
            $table->foreign('puesto_id')->references('id')->on('puestos');
            $table->date('fechacontratacion')->nullable();;
            $table->date('fechaactualizacion')->nullable();;
            $table->decimal('salarionom',8,2)->nullable();
            $table->decimal('salariodia',8,2)->nullable();
            $table->enum('periodopaga',['Semanal','Quincenal','Mensual']);
            $table->string('prestaciones')->nullable();
            $table->enum('regimen',['Sueldos y salarios','Jubilados','Pensionados']);
            $table->string('hentrada')->nullable();
            $table->string('hsalida')->nullable();
            $table->string('hcomida')->nullable();
            $table->string('banco')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('clabe')->nullable();
            $table->date('fechabaja')->nullable();
            $table->integer('tipobaja_id')->nullable()->unsigned();
            $table->foreign('tipobaja_id')->references('id')->on('tipobaja');
            $table->text('comentariobaja')->nullable();
            $table->boolean('bonopuntualidad')->default(false);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleadosdatoslab');
    }
}
