<?php

namespace App\Http\Controllers\CorteCaja;

use App\Exports\CorteCajaExport;
use App\Exports\TotalVentasExport;
use App\Exports\ClienteVentasExport;
use Carbon\Carbon;
use App\Exports\SheetsExport;

use App\Exports\CorteCajaPExport;
use App\Exports\TotalVentasPExport;
use App\Exports\ClienteVentasPExport;

use App\Exports\SheetsPExport;

use App\Exports\DatosFiscalesExport;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Venta;
use Maatwebsite\Excel\Facades\Excel;

class CorteCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now('America/Mexico_City');
        $ventasDeHoy = Venta::where('fecha', '>=', $now->format('Y-m-d'))->get();
        // return 
        return view('corte_caja.index', compact('ventasDeHoy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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

    public function export(){
        return Excel::download(new SheetsExport, 'corte_caja.xls');
    }
    public function exportV(){
        return Excel::download(new TotalVentasExport, 'corte_cajaV.xls');
    }
    public function exportC(){
        return Excel::download(new ClienteVentasExport, 'corte_cajaC.xls');
    }



    public function export2(){
        return Excel::download(new SheetsPExport, 'corte_caja.xls');
    }
    public function export2V(){
        return Excel::download(new TotalVentasPExport, 'corte_cajaV.xls');
    }
    public function export2C(){
        return Excel::download(new ClienteVentasPExport, 'corte_cajaC.xls');
    }
    public function download(Request $request)
    {
        // dd($request->fecha);
        return Excel::download(new DatosFiscalesExport($request->fecha, $request->oficina_id), 'Datos_Fiscales.xlsx');
    }
}
