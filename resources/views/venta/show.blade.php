@extends('principal')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <h4>Venta</h4>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('ventas.index') }}" class="btn btn-primary">
                        <i class="fa fa-bars"></i><strong> Lista de Ventas</strong>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-body">
                <div class="row">
                    <div class="col-4 form-group">
                        <label class="control-label">Fecha:</label>
                        <input type="text" class="form-control" value="{{$venta->fecha}}" readonly="">
                    </div>
                    <div class="col-4 form-group">
                        <label class="control-label">Cliente:</label>
                        <input type="text" class="form-control" value="{{$venta->paciente->fullname}}" readonly="">
                    </div>
                    <div class="col-4 form-group">
                        <label class="control-label">Factura:</label>
                        <input type="text" class="form-control" value="{{ $venta->requiere_factura == 1 ? 'SI' : 'NO' }}" readonly="">
                    </div>
                    <div class="col-4 form-group">
                        <label class="control-label">Folio de damage:</label>
                        @php
                        $Folio = $venta->productos()->pluck('venta_id');
                        $indice =$venta->paciente_id;
                        $id=$venta->paciente_id ;
                         $texto = strval($origen);
                         $resultado = str_replace("origin_id", "Folio de origen", $texto);

                                if($resultado =='[{"Folio de origen":null}]'){
                                    $resultado='No es de Damage';
                                }else{
                                     $resultado = substr($resultado,20,-2);
                                     $resultado = preg_replace("/[^a-zA-Z0-9]+/", "", $resultado);
                                     
                                }
                        @endphp
                        <input type="text" class="form-control" value="{{$resultado}}" readonly="">
                    </div>
                    <div class="col-4 form-group">
                        <label class="control-label">Comentario:</label>
                        <input type="text" class="form-control" value="{{$venta->comentario}}" readonly="">
                    </div>
                    <div class="col-4 form-group">
                        <label class="control-label">Folio:</label>
                        <input type="number" class="form-control" value="{{$venta->id}}" readonly="">
                    </div>
                    @if ($venta->oficina_id)
                    <div class="col-4 form-group">
                        <label class="control-label">Tienda:</label>
                        <input type="text" class="form-control" value="{{$venta->oficina->nombre}}" readonly="">
                    </div>
                    @endif
                    {{-- <div class="col-4 form-group">
                        <label class="control-label">Oficina:</label>
                        <input type="text" class="form-control" value="{{$venta->oficina->nombre}}" readonly="">
                </div> --}}
            </div>
            <div class="row">
                <h5>Productos</h5>
            </div>
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th>sku</th>
                            <th>Nombre</th>
                            <th>Precio Individual</th>
                            <th>Cantidad</th>
                            <th>Precio total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->productos as $producto)
                        <tr>
                            <td>{{$producto->sku}}</td>
                            <td>{{$producto->descripcion}}</td>
                            <td>{{$producto->precio_publico_iva}}</td>
                            <td>{{$producto->pivot->cantidad}}</td>
                            <td>{{$producto->precio_publico_iva * $producto->pivot->cantidad}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-4 form-group">
                    <label class="control-label">Subtotal:</label>
                    <input type="number" class="form-control" value="{{$venta->subtotal}}" readonly="">
                </div>
                <div class="col-4 form-group">
                    <label class="control-label">Descuento:</label>
                    <input type="text" class="form-control"
                        value="{{round($venta->subtotal-$venta->total+($venta->subtotal*0.16))}}" readonly="">
                    {{-- @if ($venta->descuento)
                            @if ($venta->promocion->tipo=='E')
                                <input type="text" class="form-control" value="0" readonly="">
                            @else
                                <input type="text" class="form-control" value="{{ $venta->subtotal-$venta->total+($venta->subtotal*0.16) }}"
                    readonly="">
                    @endif

                    @else
                    <input type="text" class="form-control" value="0" readonly="">
                    @endif --}}

                </div>
                <div class="col-4 form-group">
                    <label class="control-label">Total:</label>
                    <input type="number" class="form-control" value="{{$venta->total}}" readonly="">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h5 class="text-center text-uppercase text-muted">HISTORIAL DE CAMBIOS DE VENTA</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;"
                                    id="tablaInventario">
                                    <thead>
                                        <tr class="info">
                                            <th>TIPO CAMBIO</th>
                                            <th>RESPONSABLE</th>
                                            <th>PRODUCTO ENTREGADO</th>
                                            <th>PRODUCTO DEVUELTO</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($venta->historialCambios as $cambio)
                                        <tr>
                                            <td>{{$cambio->tipo_cambio}}</td>
                                            <td>{{$cambio->responsable->name}}</td>
                                            <td>{{$cambio->productoEntregado ? $cambio->productoEntregado->sku : 'N/D'}}</td>
                                            <td>{{$cambio->productoDevuelto ? $cambio->productoDevuelto->sku : 'N/D'}}</td>
                                            <td>{{$cambio->observaciones}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>

    </div>

</div>
</div>
<br>


<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaHistorialCambios').DataTable();
    } );
</script>

@endsection