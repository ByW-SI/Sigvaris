<?php

namespace App\Http\Controllers\Paciente;

use App\Exports\FacturasExport;
use App\Exports\Facturas2Export;
use App\Factura;
use App\Paciente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Oficina;
use App\Venta;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class FacturaController extends Controller
{

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(Auth::check()) {
                if(Auth::user()->role->facturacion)
                {
                    return $next($request);
                }                
             return redirect('/inicio');
                 
            }
            return redirect('/');           
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    	
        return view('paciente.facturas.index',['facturas'=>Factura::paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pacientes = Paciente::get();
        $oficinas = Oficina::get();
        return view('paciente.facturas.create',['pacientes'=>$pacientes, 'oficinas' => $oficinas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factura=new Factura($request->all());
        // $factura->venta_id=$request->venta_id;
        // $factura->nombre=$request->nombre;
        if($request->tipo_persona)
        {
        	$factura->fisica=1;
        	$factura->moral=0;
        }
        else
        {
        	$factura->moral=1;
        	$factura->fisica=0;
        }
        //dd($factura);
        $factura->save();
        return redirect()->route('facturas.index');
        // $factura->rfc=$request->rfc;
        // $factura
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('paciente.facturas.show',['factura'=>Factura::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function download(Request $request){
        return Excel::download(new FacturasExport($request->fecha, $request->oficina_id), 'facturas-mostrador.xlsx');
    }
    public function download2(Request $request){
        return Excel::download(new Facturas2Export($request->fecha, $request->oficina_id), 'facturas-clientes.xlsx');
    }

    public function getVentas(Paciente $paciente)
    {
    	return view('paciente.facturas.get_ventas',['ventas'=>$paciente->ventas]);
    }

    public function getPaciente(Paciente $paciente)
    {
    	return response()->json(['paciente'=>$paciente],200);
    }
}
