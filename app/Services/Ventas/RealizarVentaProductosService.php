<?php

namespace App\Services\Ventas;

use App\Producto;
use App\Venta;
use Carbon\Carbon;

class RealizarVentaProductosService
{

    public function make($venta, $productos, $request)
    {
        // dd('venta que será guardada'.$venta);
        // REALIZAMOS LA VENTA
        $venta->save();

        // POR CADA PRODUCTO COMPRADO, ALMACENAMOS LA CANTIDAD COMPRADO, EL PRECIO Y DECREMENTAMOS EL STOCK
        foreach ($productos as $i => $producto) {
            $venta->productos()->attach($producto->id, ['cantidad' => $request->cantidad[$i], 'precio' => $producto->precio_publico, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $producto->decrement('stock', $request->cantidad[$i]);
        }
    }
}
