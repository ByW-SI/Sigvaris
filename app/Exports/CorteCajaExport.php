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

class CorteCajaExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
            $now = Carbon::now('America/Mexico_City');
        $index=0;
       // dd($now);
        return Venta::where('fecha', '>=', $now->format('Y-m-d'))
            ->where('oficina_id',2)
            ->get()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                function ($Venta,$index) {

                //dd($Venta->productos()->pluck('cantidad')->sum());
                $index++;
                //dd($Venta->requiere_factura);
                return collect([
                    date('Y-m-d'),
                    Carbon::parse($Venta->fecha)->format('h:i:s'),
                    $Venta->id,
                    $index,
                    $Venta->paciente->nombre." ".$Venta->paciente->paterno." ".$Venta->paciente->materno,
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->nombre : "",
                    $Venta->paciente->ventas()->count() == 1?  "1":"2",
                    $Venta->id,
                    $Venta->empleado != null ? $Venta->empleado->nombre : "",
                    $Venta->productos != null ? $Venta->productos()->pluck('cantidad')->sum():"",
                    $Venta->total,
                    $Venta->PagoEfectivo,
                    $Venta->sigpesos,


                    $Venta->banco!= null ? $Venta->banco =="AMEX"? $Venta->PagoTarjeta:"" :"",
                    $Venta->banco!= null ? $Venta->banco =="AMEX"? $Venta->digitos_targeta:"" :"",

                    $Venta->banco!= null ? $Venta->banco !="AMEX"? $Venta->PagoTarjeta:"" :"",
                    $Venta->banco!= null ? $Venta->banco !="AMEX"? $Venta->digitos_targeta:"" :"",

                    "",
                    $Venta->requiere_factura == 1 ? "SI":"NO",
                    //Factura::where('venta_id',$Venta->id)->exists()? "Si":"No",
                    $Venta->empleado != null ? $Venta->empleado->nombre : "",
                    "",
                    "0",
                    HistorialCambioVenta::where('venta_id',$Venta->id)->exists()? "Si":"No",
                    "",
                    $Venta->promocion_id != null ? Descuento::where("id",$Venta->descuento_id)->value('nombre') : "",

                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Hora',
            'Nota de remisión',
            'Partida',
            'DETALLE PACIENTE',
            'NOMBRE DEL MÉDICO QUE ENVÍA',
            'No. Vista',
            'NOTA DE REMISION',
            'CIERRE VENTA',
            'PZAS POR PACIENTE',
            'TOTAL VENTA',
            'PAGO EFECTIVO',
            'PAGO SIGPESOS',

            'PAGO TARJETA ',
            'DIGITOS 4 ULTIMOS ',

            'PAGO TARJETA AMEX',
            'DIGITOS 4 ULTIMOS ',

            'Pago depósito',
            
            'FACTURA',
            'Generó',
            'Envió',

            'Devolución en efectivo',
            'Cambio fisico',
            'Muestra',
            'Notas Observaciones'


        ];
    }
    public function title(): string
    {
        return 'Corte de Caja';
    }
}
