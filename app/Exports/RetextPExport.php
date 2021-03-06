<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\Promocion;
use App\Retex;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class RetextPExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     $now = Carbon::now('America/Mexico_City');
    //     $Ventas=Venta::where('fecha', '>=', $now->format('Y-m-d'))->get();
    //     $retex = DB::table('retex_ventas')->where('created_at','>=',$now->format('Y-m-d'))->get();
    //     $Devoluciones = DB::table('devoluciones')->where('created_at','>=',$now->format('Y-m-d'))->get();
    //     $Dev = $Devoluciones->sum('monto')*-1;
    //     $TotalVentas=$Ventas->count();
    //     $VentasIVA= $Ventas->sum('total')+$Dev;
    //     $VentasSIVA=(($VentasIVA/1.16));
    //     $auxNu=[];
    //     $auxRe=[];
    //     $NumDoc=[];
    //     $retex= [];
    //     foreach ($Ventas as $Venta) {
    //         if ( $Venta->paciente->ventas()->count()==1) {
    //             array_push ($auxNu,$Venta->paciente->id);
    //             array_push ($retex,$Venta->retex->venta_id);
    //         }else{
    //             array_push ($auxRe,$Venta->paciente->id);
    //         }
    //         if ( $Venta->paciente->doctor != null) {
    //             array_push ($NumDoc, $Venta->paciente->doctor->id);
    //         }

    //     }
    //     $auxNu=array_unique($auxNu);
    //     $auxRe=array_unique($auxRe);
    //     $NumDoc=array_unique($NumDoc);
    //     // dd($retex);
    //     $todo = array('TotalVentas' => $TotalVentas , 'VentasIVA'=>$VentasIVA , 'VentasSIVA'=>$VentasSIVA,'auxNu'=>count($auxNu),'auxRe'=>count($auxRe),'NumDoc'=>count($NumDoc),'Dev'=>$Dev,'Retex'=>$retex);
        
    //     return collect([[
                   
                    

    //             ]]);
    //     /**return Venta::where('fecha', '>=', date('Y-m-d'))
    //         ->where('oficina_id',1)
    //         ->get()
    //         //->first()
    //         //->pluck('productos')
    //         ->flatten()
    //         ->map(function ($Venta,$todo) {
    //             dd($todo);
                
    //         });**/

    // }
    public function collection()
    {
        $index=0;
        return Venta::where('fecha', '>=', date('Y-m-d'))
            ->where('oficina_id',2)
            ->get()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                function ($Venta,$index) {


                return collect([
                    DB::table('retex_ventas')->where('venta_id','=',$Venta->id)->exists()?DB::table('retex_ventas')->select('venta_id')->where('venta_id','=',$Venta->id)->value('venta_id'):"",

                    Venta::where('id',$Venta->id)->value('total'),

                     DB::table('retex_ventas')->where('venta_id','=',$Venta->id)->exists()?str_replace('"folio":','',DB::table('retex_ventas')->select('folio')->where('venta_id','=',$Venta->id)->get()):"",

                     DB::table('retex_ventas')->where('venta_id','=',$Venta->id)->exists()?str_replace('"SKU":','',DB::table('retex_ventas')->select('SKU')->where('venta_id','=',$Venta->id)->get() ):"",
                    
                    HistorialCambioVenta::where('venta_id',$Venta->id)->where('tipo_cambio','RETEX DEL PRODUCTO')? HistorialCambioVenta::where('destinate_id','=',$Venta->id)->value('venta_id'):"",
                    
                    DB::table('garex_ventas')->where('venta_id','=',$Venta->id)->exists()? str_replace('"folio":','',DB::table('garex_ventas')->select('folio')->where('venta_id','=',$Venta->id)->get() ):"",


                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Nota de remision',
            'Total que se pago:',
            'Folio Garext',
            'SKU',
            'Garex Nota de remision',
            'Garex Folio'
            
        ];
    }
    public function title(): string
    {
        return 'Retext';
    }
}
