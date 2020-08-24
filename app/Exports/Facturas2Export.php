<?php

namespace App\Exports;

use App\Factura;
use App\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Facturas2Export implements FromCollection, WithHeadings
{
    public function __construct($fecha, $oficina_id)
    {
        $this->fecha = $fecha;
        $this->oficina_id = $oficina_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    //TOTAL de la venta 
    public function TotalVentasinDescuento($id)
    {
        $Venta=Venta::where('id',$id)->first();
        $Total=0;
        foreach ($Venta->productos as $Producto) {
            $Total=$Total+($Producto->pivot->precio*$Producto->pivot->cantidad);
            # code...
        }
        if ($Total==0) {
            return 1; 
        }
        return $Total+($Total*.16); 
        # code...
    }

    public function collection()
    {

        $ventas = Venta::where('requiere_factura', 1);

        if (!is_null($this->fecha)) {
            $ventas = $ventas->where('fecha', "=", $this->fecha);
        }

        if (!is_null($this->oficina_id)) {
            $ventas = $ventas->where('oficina_id', $this->oficina_id);
        }

        ini_set('memory_limit', '-1');

            

            
        return collect(
            $ventas->where('requiere_factura', 1)->with('productos.ventas')->get()->pluck('productos')->flatten()->map(function ($producto) {
                $venta = Venta::find($producto->pivot->venta_id);
                return [
                    'clave' => 1,
                    'cliente' => 'MOST ' . strtoupper(substr($venta->oficina->nombre, 0, 3)),
                    'fecha_de_elaboracion' => substr($venta->fecha,0,11),
                    'numero_almacen_cabecera' => $venta->oficina->nombre == 'Polanco' ? 2 : 7,
                    'numero_de_moneda' => 1,
                    'tipo_de_cambio' => 1,
                    'OBSERVACIONES' => 'Folio: ' . $venta->id,
                     //'observaciones' => 'Folio: ' . $venta->venta_id ,
                    // 'observaciones' => 'tienda: ' . $venta->oficina->nombre . " fecha venta: " . date('d-m-Y'),
                    'clave_del_vendedor' => strtoupper(substr($venta->oficina->nombre, 0, 3)) . ", " . $venta->oficina->nombre == 'Polanco' ? 8 : 7,
                    'nombre_del_paciente' => $venta->paciente ? $venta->paciente->full_name : '',
                    'fecha_entrega' => date('d-m-Y'),
                    'fecha_vencimiento' => date('d-m-Y'),
                    'precio_producto' => $producto->precio_publico_iva,
                    'descuento' => $venta->promocion ? (($this->TotalVentasinDescuento($venta->id)-$venta->total)/$this->TotalVentasinDescuento($venta->id)) * 100 : '0.00',
                    'descuento_02' => '',
                    'descuento_03' => '',
                    'comision' => '',
                    'esquema_impuestos' => 1,
                    'clave_articulo' => $producto->sku,
                    'cantidad' => $producto->pivot->cantidad,
                    'ieps' => '',
                    'impuesto_02' => '',
                    'impuesto_03' => '',
                    'iva' => 16,
                    'numero_almacen' => $venta->oficina->nombre == 'Polanco' ? 2 : 7,
                    'observaciones' => '',
                ];
            }),
        );
    }

    public function headings(): array
    {
        return [
            'CLAVE',
            'OFICINA',
            'FECHA DE ELABORACIÓN',
            'NÚM. DE ALMACÉN',
            'NÚM. MONEDA',
            'TIPO DE CAMBIO',
            'OBSERVACIONES',
            'OFICINA',
            'NOMBRE PACIENTE',
            'FECHA ENTREGA',
            'FECHA VENCIMIENTO',
            'PRECIO',
            'DESC. 1',
            'DESC. 2',
            'DESC. 3',
            'COMISIÓN',
            'CLAVE DE ESQUEMA DE IMPUESTOS',
            'CLAVE DEL ARTICULO',
            'CANTIDAD',
            'I.E.P.S.',
            'IMPUESTO 2',
            'IMPUESTO 3',
            'I.V.A.',
            'NÚM. DE ALMACÉN',
            'OBSEREVACIONES DE PARTIDA'
        ];
    }

    
}
