<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGarexVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garex_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('venta_id');
            $table->string('SKU');
            $table->string('folio');
            $table->date('fecha_fin');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_garex_ventas');
    }
}
