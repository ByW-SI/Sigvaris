@extends('principal')
@section('content')
<meta name="csrf-token" content="{{ Session::token() }}"> 
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-3">
                    <h4>Actualizar Producto Negado</h4>
                </div>
                <div class="col-3 text-center">
                    <a href="{{ url('negado/show') }}" class="btn btn-primary">
                        <i class="fa fa-bars"></i><strong> Lista de Productos Negados</strong>
                    </a>
                </div>
                <div class="col-3 text-center">
                    <a href="{{route('negado.index')}}" class="btn btn-primary">
                        <strong> Crear un producto Negado</strong>
                    </a>
                </div>
            </div>
            
        </div>
        <div class="card-body">
            <form role="form" id="form-cliente" method="POST" action="{{url('negado/'.$negado->id.'/editar')}}" name="form">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="paciente_id" value="{{$negado->paciente_id}}">
                        <input type="hidden" name="producto_id" value="{{$negado->producto_id}}">
                        <div class="col-3 form-group">
                            <label id="No_repetido" class="control-label">✱SKU producto:</label>
                            <input type="text"  class="form-control" value="{{$negado->producto->sku}}" readonly=""  required="">
                        </div>
                        <div class="col-3 form-group">
                            <label id="No_repetido" class="control-label">✱Paciente:</label>
                            <input type="text"  class="form-control" value="{{$negado->paciente->fullname}}" readonly=""  required="">
                        </div>
                        <div class="col-3 form-group">
                            <label for="actual">✱Fecha actual</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{$negado->fecha}}" 
                                readonly="" required="">
                        </div>
                        <div class="col-3 form-group">
                            <label for="actual">Fecha posible entrega:</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value="{{$negado->fecha_entrega}}" readonly>
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label">Producto que se entrego en lugar del negado:</label>
                            <input type="text" name="producto2" class="form-control"  id="producto2"  value="{{$negado->productoEntregado ? $negado->productoEntregado->sku : ''}}" readonly>
                        </div>
                        <div class="col-3 form-group">
                            <label class="control-label"><br>Comentarios :</label>
                            <input type="text" name="comentarios" class="form-control" step="1"  id="comentarios"  value="{{$negado->comentarios}}" readonly>
                        </div>
                        
                        <input type="hidden" name="oficina_id" value="{{session('oficina')}}">
                    </div>
                </div>
                {{-- <div class="card-footer">
                    <div class="row">
                        <div class="col-4 offset-4 text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Guardar
                            </button>
                        </div>
                        <div class="col-4 text-right text-danger">
                            ✱Campos Requeridos.
                        </div>
                    </div>
                </div> --}}
            </form>
        </div>
    </div>
</div>

@endsection