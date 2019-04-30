@extends('principal')
@section('content')
<div class="container">

	<div class="card">
        
        <form class="" action="{{route('descuentos.update',['descuento'=>$descuento])}}" method="post">
            {{ csrf_field() }}
            @method('PUT')
            <div class="card-header">
                <h1>Editar Descuento </h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required="" value="{{ 
                        $descuento->nombre }}">
                    </div>
                    <div class="form-group col-3">
                        <label for="tipo">Tipo</label>
                        <select class="form-control" name="tipo" id="tipo" required="">
                        	{{-- <option selected="{{$descuento->tipo }}"></option> --}}
                            <option value="">Seleccionar...</option>
                            <option value="Porcentaje">Porcentaje</option>
                            <option value="Efectivo">Efectivo</option>                        
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label for="valor">Valor</label>
                        <input type="value" step="0.01" name="valor" class="form-control" id="valor" required="" value="{{ 
                        $descuento->valor }}">
                    </div>
                    <div class="col-3 pt-4">
                        <button type="submit" class="btn btn-success btn-lg btn-block">Agregar</a>
                    </div>
                </div>
            </div>
        </form>
</div>

@endsection