<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\Promocion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClienteVentasExport implements FromCollection, WithHeadings,WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $now = Carbon::now('America/Mexico_City');
        return Venta::where('fecha', '>=',$now->format('Y-m-d'))
            ->where('oficina_id',2)
            ->get()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                function ($Venta) {
                    $SkuRe="";
                    $contador = $Venta->productos()->pluck('cantidad');
                    $aux = 0;
                    foreach ($Venta->productos as $producto ) {
                       
                        $SkuRe.=$producto->sku." - ".$contador[$aux]."| ";
                        $aux++;
                        
                    }
                return collect([
                    $Venta->id,
                    date('Y-m-d'),                    
                    $Venta->paciente->nombre." ".$Venta->paciente->paterno." ".$Venta->paciente->materno,
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->nombre : "",
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->consultorios()->first() != null ? $Venta->paciente->doctor->consultorios()->first()->nombre:"" : ""    ,
                    "",
                    $Venta->paciente->ventas()->count() == 1?  "1":"2",
                    $Venta->productos != null ? $Venta->productos()->pluck('cantidad')->sum():"",
                    $SkuRe,
                    "",
                    "",
                    "",
                    $Venta->paciente->mail,
                    $Venta->paciente->telefono,
                    $Venta->paciente->celular

                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Nota de remisión',
            'Fecha de compra',
            'Nombre del paciente',
            'Nombre del Doctor',
            'Hospital ',
            'fecha receta',
            'Numero de visita',
            'Cantidad ',
            'Sku Vendido',
            'damage',
            'Producto negado ',
            'Estilo negado',
            'mail',
            'telefono',
            'celular'



        ];
    }
    public function title(): string
    {
        return 'Clientes';
    }
}
