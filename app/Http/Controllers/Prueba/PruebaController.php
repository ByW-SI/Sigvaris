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
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PruebaController extends Controller
{

    public function index()
    {
        return Doctor::find(1)->pacientes()
            ->whereHas('ventas', function (Builder $query) {
                $query->where('fecha', '>=', '2019-01-01')
                    ->where('fecha', '<=', '2019-12-31');
            })
            ->withCount("ventas")
            ->with('ventas')
            ->having('ventas_count', '<=', 1)
            ->get();
    }

    public function CrmVentasTotales()
    {
        $Ventas=Venta::get();
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
        }
    }
}
