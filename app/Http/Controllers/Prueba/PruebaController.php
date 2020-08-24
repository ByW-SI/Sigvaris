<?php

namespace App\Http\Controllers\Prueba;

use App\Doctor;
use App\Empleado;
use App\EmpleadosDatosLab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Paciente;
use App\Producto;
use App\Puesto;
use App\Venta;
use App\Crm;
use App\Factura;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PruebaController extends Controller
{

    public function index()
    {
        return Venta::where('fecha','>=','2020-01-01')->where('fecha','<=','2020-04-31')->get();
        // return Venta::with('productos')->get()->filter( function($venta){
        //     return $venta->pluck('productos')->flatten()->count() > 1;
        // } );
    }
    public function GenerarTiendasVentas()
    {
        $Ventas = Venta::get();
        foreach ($Ventas as $Venta) {
            $Venta->update(['oficina_id'=>$Venta->paciente->oficina_id]);
        }
    }
    public function FacturasRFC()
    {
        $Facturas=Factura::get();
        foreach ($Facturas as $Factura) {
            $Factura->update(['rfc'=>$Factura->venta->paciente->rfc]);
        }
    }
    public function GenerarTiendasCRMS()
    {
        $Crms = Crm::get();
        foreach ($Crms as $Crm) {
            $Crm->update(['oficina_id'=>$Crm->paciente->oficina_id]);
        }
    }

   



    public function CrmVentasTotales()
    {
        $Ventas = Venta::get();
        foreach ($Ventas as $Venta) {
            $CRM = new Crm(
                array(
                    'paciente_id' => $Venta->paciente_id,
                    'estado_id'   => 1,
                    'hora'        => '00:00',
                    'forma_contacto' => 'Telefono',
                    'fecha_contacto' => \Carbon\Carbon::parse($Venta->fecha)->addMonths(5),
                    'fecha_aviso' => \Carbon\Carbon::parse($Venta->fecha)->addMonths(5)
                )
            );
            $CRM->save();
            $CRM = new Crm(
                array(
                    'paciente_id' => $Venta->paciente_id,
                    'estado_id'   => 5,
                    'hora'        => '00:00',
                    'forma_contacto' => 'Telefono',
                    'fecha_contacto' => \Carbon\Carbon::parse($Venta->fecha)->addDays(8),
                    'fecha_aviso' => \Carbon\Carbon::parse($Venta->fecha)->addDays(8)
                )
            );
            $CRM->save();

        }
    }
}
