@extends('principal')

@section('content')

<div class="container">
    <div class="row">
        {{-- CONTENEDOR TITULO --}}
        <div class="col-12">
            <h3 class="text-center text-uppercase text-muted">CORTE DE CAJA</h3>
        </div>
        {{-- CONTENEDOR FECHA Y HORA --}}
        <div class="col-12 col-lg-6 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                          @php
                             $date =  new DateTime(); 
                             $date->modify('-5 hours');                       
                          @endphp
                        <div class="col-12 col-lg-4">
                            <label for="" class="text-uppercase text-muted">Usuario</label>
                            <input type="text" value="{{Auth::user()->name}}" class="form-control" readonly>
                        </div>
                        <div class="col-12 col-lg-5">
                            <label for="" class="text-uppercase text-muted">Fecha</label>
                            <input type="date" value="{{$date->format('Y-m-d')}}" class="form-control" readonly>
                        </div>
                        <div class="col-12 col-lg-4">
                            <label for="" class="text-uppercase text-muted">Hora</label>
                            <input type="text" value="{{$date->format('H:i:s')}}" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 mt-4">
            <div class="card">
                <div class="card-body">
                    <label class="text-uppercase text-muted">Exportar excel</label>
                    <a href="{{route('corte-caja.export.polanco')}}" class="form-control btn btn-success btn-block rounded-0">EXPORTAR Polanco</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 mt-4">
            <div class="card">
                <div class="card-body">
                    <label class="text-uppercase text-muted">Exportar excel</label>
                    <a href="{{route('corte-caja.export.perisur')}}" class="form-control btn btn-success btn-block rounded-0">EXPORTAR Perisur</a>
                </div>
            </div>
        </div>
        {{-- CONTENEDOR TABLA --}}
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover" id="tabla-corte-caja">
                        <thead>
                            <tr class="info">
                                <th>SKU</th>
                                <th>CANTIDAD</th>
                                <th>UNITARIO (SIN IVA)</th>
                                <th>TOTAL (SIN IVA)</th>
                                <th>TIENDA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventasDeHoy as $venta)
                            @foreach ($venta->productos as $producto)
                            <tr>
                                <td>{{$producto->sku}}</td>
                                <td>{{$producto->pivot->cantidad}}</td>
                                <td>{{$producto->pivot->precio}}</td>
                                <td>{{$producto->pivot->precio * $producto->pivot->cantidad}}</td>
                                <td>{{$venta->oficina->nombre}}</td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 my-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <label for="" class="text-uppercase text-muted">SUBTOTAL</label>
                            <input type="text" class="form-control" readonly value="{{$ventasDeHoy->pluck('subtotal')->flatten()->sum()}}">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label for="" class="text-uppercase text-muted">IVA</label>
                            <input type="text" class="form-control" readonly value="{{$ventasDeHoy->pluck('subtotal')->flatten()->sum() * 0.16}}">
                        </div>
                        <div class="col-12 col-lg-4">
                            <label for="" class="text-uppercase text-muted">TOTAL (CON IVA)</label>
                            <input type="text" class="form-control" readonly value="{{$ventasDeHoy->pluck('subtotal')->flatten()->sum() * 1.16}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
		$('#tabla-corte-caja').DataTable({
			"language": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			}
		});
	});

</script>

@endsection