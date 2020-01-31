<?php

namespace App\Http\Controllers\Inventario;

use App\HistorialModificacionInventario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInventario;
use App\Producto;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function index()
    {
        // dd( session('oficina') );
        $productos = Producto::get();
        return view('producto.inventario.index', compact('productos'));
    }

    public function edit($id)
    {
        $producto = Producto::find($id);
        $usuario = Auth::user();
        // dd($usuario);
        return view('producto.inventario.edit', compact('producto', 'usuario'));
    }

    public function update(UpdateInventario $request)
    {

        $producto = Producto::find($request->input('productoId'));
        $stockNuevo = $producto->stock - $request->input('numProductosBaja');
        $stockNuevo = $stockNuevo + $request->input('numProductosAlta');

        // AÑADIMOS EL CAMBIO DE INVENTARIO AL HISTORIAL
        $historialModificacionInventario = HistorialModificacionInventario::create([
            'user_id' => $request->input('responsable'),
            'producto_id' => $producto->id,
            'stock_anterior' => $producto->stock,
            'stock_nuevo' => $stockNuevo,
            'motivo' => $request->input('motivo'),
        ]);

        // ACTUALIZAMOS LA INFORMACIÓN DEL PRODUCTO
        $producto->update([
            'descripcion' => $request->input('descripcion'),
            'sku' => $request->input('sku'),
            'swiss_id' => $request->input('swissId'),
            'upc' => $request->input('upc'),
            'stock' => $stockNuevo,
        ]);

        $producto->save();

        return redirect()->route('productos.inventario')->with('status', '¡El inventario ha sido actualizado!');
    }

    public function historial()
    {
        $historialModificacionesInventario = HistorialModificacionInventario::get();
        return view('producto.inventario.historial', compact('historialModificacionesInventario'));
    }
}
