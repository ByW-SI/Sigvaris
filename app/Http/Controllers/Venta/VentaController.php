<?php

namespace App\Http\Controllers\Venta;

use Carbon\Carbon;
use App\Venta;
use App\Paciente;
use App\Producto;
use App\Descuento;
use App\Promocion;
use App\Doctor;
use App\Empleado;
use App\Crm;
use App\DatoFiscal;
use App\Folio;
use App\Banco;
use App\Factura;
use App\Sigpesosventa;
use App\HistorialCambioVenta;
use App\ProductoDamage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Ventas\RealizarVentaProductosService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{

    public function __construct(RealizarVentaProductosService $realizarVentaProductos)
    {
        $this->realizarVentaProductosService = $realizarVentaProductos;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $medicos = Doctor::get();

        $ventas = Venta::orderBy('id', 'desc');

        if ($request->fecha_inicio) {
            $ventas = $ventas->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->fecha_fin) {
            $ventas = $ventas->where('fecha', '<=', $request->fecha_fin);
        }
        if ($request->numero_folio) {
            $ventas = $ventas->where('id', $request->numero_folio);
        }
        if ($request->apellido_paterno) {
            $ventas = $ventas->whereHas('paciente', function(Builder $query) use ($request){
                $query->where('paterno', 'LIKE', '%' . $request->apellido_paterno .'%');
            } );
        }

        //Poner ventas en historial cortes de caja 
        $ventas = $ventas->where('oficina_id', session('oficina'));
        $ventas = $ventas->orderBy('fecha', 'desct')->paginate(5);
        return view('venta.index_all', ['ventas' => $ventas, 'medicos' => $medicos])->withInput($request->input());
    }

    public function indexConPaciente(Paciente $paciente)
    {
        return view('venta.index', ['ventas' => $paciente->ventas, 'paciente' => $paciente]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hoy = Carbon::now('America/Mexico_City')->toDateString();
        $descuentos = Descuento::where('inicio', '<=', $hoy)->where('fin', '>=', $hoy)->get();
        $productos = Producto::where('id', '<', 1)->get();
        $pacientes = Paciente::where('id', '<', 1)->get();
        $empleadosFitter = Empleado::fitters()->get();
        $Bancos = Banco::get();
        return view('venta.create', [
            'pacientes' => null,
            'paciente' => null,
            'descuentos' => $descuentos,
            'productos' => $productos,
            'folio' => Venta::count() + 1,
            'empleadosFitter' => $empleadosFitter,
            'Folios' => Folio::get(),
            'Bancos' => $Bancos
        ]);
    }

    public function createConPaciente(Paciente $paciente)
    {
        //dd($paciente);
        $hoy = Carbon::now()->toDateString();
        $descuentos = Descuento::where('inicio', '<=', $hoy)->where('fin', '>=', $hoy)->get();
        $productos = Producto::where('id', '<', 1)->get();
        $pacientes = Paciente::get();
        $empleadosFitter = Empleado::fitters()->get();
        $Bancos = Banco::get();
        //dd($pacientes);
        return view('venta.create', [
            'pacientes' => $pacientes,
            'paciente' => $paciente,
            'descuentos' => $descuentos,
            'productos' => $productos,
            'folio' => Venta::count() + 1,
            'empleadosFitter' => $empleadosFitter,
            'Folios' => Folio::get(),
            'Bancos' => $Bancos
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!isset($request->producto_id) || is_null($request->producto_id)) {
            return redirect()
                ->back()
                ->withErrors(['No se seleccionó ningún producto.'])
                ->withInput($request->input());
        }
        //dd($request->PagoEfectivo+$request->PagoTarjeta==$request->total);
        if (!($request->PagoEfectivo + $request->PagoTarjeta == round($request->total, 2))) {
            return redirect()
                ->back()
                ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
                ->withInput($request->input());
        }
        /*
        if (!is_null($request->digitos_targeta) && ($request->digitos_targeta<1000)) {
            return redirect()
                ->back()
                ->withErrors(['Error con ultimos 4 digitos de tarjeta'])
                ->withInput($request->input());
        }
        if (isset($request->descuentoCum)&&$request->descuentoCum!=0) {
            # code...
            $request->cumpleDes=1;
        }*/
        // PREPARAR DATOS DE LA VENTA
        $venta = new Venta($request->all());
        $venta->oficina_id = session()->get('oficina');

        // dd($venta);

        // GUARDAMOS EL FITTER DE LA VENTA
        if ($request->empleado_id) {
            $venta->empleado_id = $request->empleado_id;
        } else {
            $venta->empleado_id = Auth::user()->empleado->id;
            // dd('Empleado fitter'.Auth::user()->empleado );
        }

        // dd('VENTA QUE SERÁ GUARDADA'.$venta);

        $productos = Producto::find($request->producto_id);
        //agrgar codigo para hacer crm 

        // REALIZAR VENTA
        $this->realizarVentaProductosService->make($venta, $productos, $request);

        if ($request->facturar == "1") {
            $venta->update(['requiere_factura' => 1]);
                DatoFiscal::updateOrCreate(
                ['paciente_id' => $request->paciente_id],
                [
                    'calle' => $request->calle,
                    'tipo_persona' => $request->tipo_persona,
                    'nombre_o_razon_social' => $request->nombre_o_razon_social,
                    'regimen_fiscal' => $request->regimen_fiscal,
                    'correo' => $request->correo,
                    'rfc' => $request->rfc,
                    'homoclave'=> $request->homoclave,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
                    'uso_cfdi' => $request->uso_cfdi
                ]
             );
            $Factura = new Factura(
                    array(
                    'venta_id'=>$venta->id,
                    'nombre'=>$venta->paciente->getFullnameAttribute(),
                    'rfc'=>$request->rfc,
                    'regimen_fiscal'=> $request->regimen_fiscal,
                    'correo'=>$request->correo,
                    'calle'=> $request->calle,
                    'num_ext'=>$request->num_ext,
                    'num_int'=>$request->num_int,      
                    'ciudad'=>$request->ciudad,
                    'municipio'=>$request->alcaldia_o_municipio,              
                    'cp' =>$request->codigo_postal
            )
          );
          $Factura->save();  
        }

            
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 1,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addMonths(5),
                'fecha_aviso' => Carbon::now()->addMonths(5),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 5,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addDays(8),
                'fecha_aviso' => Carbon::now()->addDays(8),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        //Sigpesos 

        if ($request->input('tipoPago') == 4 || $request->input('tipoPago') == 3) {
            # code...
             if ($request->input('sigpesos_usar')>0) {
                foreach ($request->folio as $key => $folio) {
                    # code...
                    $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key]
                    ]);
                    $Sigpesos->save();
                }
            }
        }

        
        $Paciente=Paciente::where("id",$request->paciente_id)->first();
        $Paciente->update(['saldo_a_favor' => $request->saldo_a_favor]);
        // REDIRIGIR A LAS VENTAS REALIZADAS
        return redirect()->route('ventas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        $origen = DB::table('productos_damage')->select('origin_id')->where('destinate_id',$venta->id)->get();

        return view('venta.show', ['venta' => $venta,'origen'=>$origen]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        return view('venta.edit', ['venta' => $venta]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        $venta->update($request->all());
        return view('venta.show', ['venta' => $venta]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index');
    }

    public function getVentas(Request $request)
    {
        $prod = [];
        $ventasxprenda = [];

        // OBTENEMOS LAS PRENDAS POR EL NUMERO DE PIEZAS
        /*if ($request->num_prendas != "" && $request->num_prendas != "0") {
            $ventas = Venta::with('paciente', 'descuento')->where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)->get();
            foreach ($ventas as $v) {
                if ($v->productos->count() == $request->num_prendas)
                    $ventasxprenda[] = $v;
            }
            $ventas = [];
            foreach ($ventasxprenda as $v)
                $ventas[] = $v;
        } else*/
        $ventas = Venta::where('oficina_id', session('oficina'));
        $ventas = $ventas->with('paciente', 'descuento')->where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)->get();

        // Obtención de Las ventas que contengan la prenda o prendas que se introdujeron en el campo prenda
        $arr = [];
        /*if ($request->prenda != "") {
            $query = $request->prenda;
            $wordsquery = explode(' ', $query);
            $total_ventas = Venta::where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)->get();
            foreach ($total_ventas as $venta) {
                $productos = $venta->productos()->where(function ($q) use ($wordsquery) {
                    foreach ($wordsquery as $word) {
                        $q->orWhere('sku', 'LIKE', "%$word%")
                            ->orWhere('descripcion', 'LIKE', "%$word%")
                            ->orWhere('line', 'LIKE', "%$word%")
                            ->orWhere('upc', 'LIKE', "%$word%")
                            ->orWhere('precio_publico', 'LIKE', "%$word%")
                            ->orWhere('swiss_id', 'LIKE', "%$word%");
                    }
                })->get();
                if ($productos->count() != 0)
                    $arr[] = $venta;
            }
            //dd($arr);
        }*/

        // Combinar las ventas de acuerdo a las dos busquedas anteriores
        $ventas_final = [];
        foreach ($ventas as $venta) {
            if (count($arr) != 0) {
                foreach ($arr as $v) {
                    if ($venta->id == $v->id) {
                        $ventas_final[] = $venta;
                    }
                }
            } else
                $ventas_final[] = $venta;
        }

        // Obtencion de las prendas MAS o MENOS vendidas
        /*if ($request->mas != "")
            $consulta = DB::select("SELECT producto_id, SUM(cantidad) AS TotalVentas FROM producto_venta GROUP BY producto_id ORDER BY SUM(cantidad) DESC LIMIT 0 , 30 ");
        elseif ($request->menos != "")
            $consulta = DB::select("SELECT producto_id, SUM(cantidad) AS TotalVentas FROM producto_venta GROUP BY producto_id ORDER BY SUM(cantidad) LIMIT 0 , 100 ");
        else
            $consulta = [];
        foreach ($consulta as $productos) {
            $prod[] = ["0" => Producto::find($productos->producto_id), "1" => $productos->TotalVentas];
        }*/
        return response()->json(["ventas" => $ventas_final, "consulta" => $prod]);
    }

    public function getVentasClientes(Request $request)
    {
        if ($request->tipo == "primero") {
            $consulta = DB::select("SELECT paciente_id FROM ventas GROUP BY paciente_id HAVING COUNT(*) = 1 ");
        } elseif ($request->tipo == "consecutivo") {
            $consulta = DB::select("SELECT paciente_id FROM ventas GROUP BY paciente_id HAVING COUNT(*) > 1 ");
        } else {
            $consulta = [];
        }

        $ventas = [];
        foreach ($consulta as $paciente) {
            if ($request->desde && $request->hasta) {
                $ventastemp = Venta::where('paciente_id', $paciente->paciente_id)
                    ->where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)
                    ->get();
            } else
                $ventastemp = Venta::where('paciente_id', $paciente->paciente_id)->get();

            foreach ($ventastemp as $v) {
                $cantidad = 0;
                foreach ($v->productos as $prod) {
                    $cantidad += $prod->pivot->cantidad;
                }
                $ventas[] = ['venta' => $v, 'cantidad' => $cantidad];
            }
        }
        $suma_ventas = 0;
        $sumatoria_pacientes = [];
        foreach ($ventas as $vent) {
            $suma_ventas += $vent['venta']->total;
            $val = 1;
            foreach ($sumatoria_pacientes as $p) {
                if ($p == $vent['venta']->paciente->id)
                    $val = 0;
            }
            if ($val)
                array_push($sumatoria_pacientes, $vent['venta']->paciente->id);
        }
        $totalClientes = count($sumatoria_pacientes);
        return response()->json(["ventas" => $ventas, 'total' => $suma_ventas, 'suma_pacientes' => $totalClientes]);
    }



    public function ventaDamage(Request $request)
    {
        $saldo_a_favor=$request->input('montonegativo');
        
        // PREPARAR DATOS DE LA VENTA
        $venta = new Venta($request->all());
        $venta->oficina_id = session()->get('oficina');

        // GUARDAMOS EL FITTER DE LA VENTA
        if ($request->empleado_id) {
            $venta->empleado_id = $request->empleado_id;
        } else {
            $venta->empleado_id = Auth::user()->empleado->id;
            // dd('Empleado fitter'.Auth::user()->empleado );
        }

        // dd('VENTA QUE SERÁ GUARDADA'.$venta);

        $productos = Producto::find($request->producto_id);
        //agrgar codigo para hacer crm 

        // REALIZAR VENTA
        $this->realizarVentaProductosService->make($venta, $productos, $request);

        if ($request->facturar == "1") {
            $venta->update(['requiere_factura' => 1]);
            DatoFiscal::updateOrCreate(
                ['paciente_id' => $request->paciente_id],
                [
                    'calle' => $request->calle,
                    'tipo_persona' => $request->tipo_persona,
                    'nombre_o_razon_social' => $request->nombre_o_razon_social,
                    'regimen_fiscal' => $request->regimen_fiscal,
                    'correo' => $request->correo,
                    'rfc' => $request->rfc,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
                    'uso_cfdi' => $request->uso_cfdi
                ]
            );
        }
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 1,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addMonths(5),
                'fecha_aviso' => Carbon::now()->addMonths(5),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 5,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addDays(8),
                'fecha_aviso' => Carbon::now()->addDays(8),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
       

        if ($request->input('tipoPago') == 4 || $request->input('tipoPago') == 3) {
            # code...
            if ($request->input('sigpesos_usar')>0) {
                foreach ($request->folio as $key => $folio) {
                    # code...
                    $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key]
                    ]);
                    $Sigpesos->save();
                }
            }
        }


        $HistorialCambioVenta = new HistorialCambioVenta(
            array(
                'tipo_cambio' => "Damage",
                'responsable_id' => Auth::user()->id,
                'venta_id' => $request->VentaAnterior,
                'observaciones' => '',
                'producto_devuelto_id' => $request->productoDevuelto,
                'producto_entregado_id' => $productos[0]->id
            )
        );


        $productosDamage = new ProductoDamage;
        $productosDamage->producto_id = $request->productoDevuelto;
        $productosDamage->tipo_damage = $request->TipoDamage;
        $productosDamage->user_id = Auth::user()->id;
        $productosDamage->destinate_id =$request->folio_nuevo;
        $productosDamage->origin_id = $request->VentaAnterior;
        $productosDamage->descripcion = $request->DesDamage;
        $productosDamage->save();

        $HistorialCambioVenta->save();
        
        $Paciente=Paciente::where("id",$request->paciente_id)->first();
        $Paciente->update(['saldo_a_favor' => $saldo_a_favor]);

        // REDIRIGIR A LAS VENTAS REALIZADAS
        return redirect()->route('ventas.index');
    }


    public function ventaCambio(Request $request)
    {
        $saldo_a_favor=$request->input('montonegativo');
        
        // PREPARAR DATOS DE LA VENTA
        $venta = new Venta($request->all());
        $venta->oficina_id = session()->get('oficina');

        // GUARDAMOS EL FITTER DE LA VENTA
        if ($request->empleado_id) {
            $venta->empleado_id = $request->empleado_id;
        } else {
            $venta->empleado_id = Auth::user()->empleado->id;
            // dd('Empleado fitter'.Auth::user()->empleado );
        }

        // dd('VENTA QUE SERÁ GUARDADA'.$venta);

        $productos = Producto::find($request->producto_id);
        //agrgar codigo para hacer crm 

        // REALIZAR VENTA
        $this->realizarVentaProductosService->make($venta, $productos, $request);

        if ($request->facturar == "1") {
            $venta->update(['requiere_factura' => 1]);
            DatoFiscal::updateOrCreate(
                ['paciente_id' => $request->paciente_id],
                [
                    'calle' => $request->calle,
                    'tipo_persona' => $request->tipo_persona,
                    'nombre_o_razon_social' => $request->nombre_o_razon_social,
                    'regimen_fiscal' => $request->regimen_fiscal,
                    'correo' => $request->correo,
                    'rfc' => $request->rfc,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
                    'uso_cfdi' => $request->uso_cfdi
                ]
            );
        }

       

        if ($request->input('tipoPago') == 4 || $request->input('tipoPago') == 3) {
            if ($request->input('sigpesos_usar')>0) {
                foreach ($request->folio as $key => $folio) {
                    # code...
                    $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key]
                    ]);
                    $Sigpesos->save();
                }
            }
        }

        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 1,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addMonths(5),
                'fecha_aviso' => Carbon::now()->addMonths(5),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 5,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addDays(8),
                'fecha_aviso' => Carbon::now()->addDays(8),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        
        HistorialCambioVenta::create([
            'tipo_cambio' => 'CAMBIO PRODUCTO',
            'responsable_id' => Auth::user()->id,
            'venta_id' => $request->VentaAnterior,
            'producto_entregado_id' =>  $productos[0]->id,
            'producto_devuelto_id' => $request->productoDevuelto,
            'observaciones' => $request->observacionesDevuelto
        ]);

        $ProductoDevuelto = Producto::where('id', $request->productoDevuelto)->first();

        $ProductoDevuelto->update([
            'stock' => $ProductoDevuelto->stock + 1
        ]);

        $Paciente=Paciente::where("id",$request->paciente_id)->first();
        $Paciente->update(['saldo_a_favor' => $saldo_a_favor]);

        // REDIRIGIR A LAS VENTAS REALIZADAS
        return redirect()->route('ventas.index');
    }
}


//SELECT `producto_venta`.`producto_id`, SUM(`producto_venta`.`cantidad`) AS TotalVentas FROM `producto_venta` GROUP BY `producto_venta`.`producto_id` ORDER BY SUM(`producto_venta`.`cantidad`) DESC LIMIT 0 , 30 
