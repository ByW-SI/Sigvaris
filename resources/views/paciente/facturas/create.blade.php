@extends('principal')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('facturas.index') }}" class="btn btn-primary rounded-0">
                <i class="fa fa-bars"></i><strong> Lista de facturas</strong>
            </a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <label for="" class="text-uppercase text-muted" >📅 FECHA</label>
                            <input type="date" name="fecha" class="form-control" id="fecha">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="" class="text-uppercase text-muted" >🏛️ SUCURSAL</label>
                            <select name="oficina_id" class="form-control" id="oficina">
                                <option value="">Seleccionar</option>
                                @foreach ($oficinas as $oficina)
                                    <option value="{{$oficina->id}}">{{$oficina->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{route('facturas.download')}}" method="POST" class="form-inline float-right">
                                @csrf
                                <input type="text" name="oficina_id" id="oficinaIdFactura" style="display:none">
                                <input type="text" name="fecha" id="fechaFactura" style="display:none">
                                <button type="submit" class="btn btn-primary rounded-0">FACTURA MOSTRADOR</button>
                            </form>
                            <br><br><br>
                            <form action="{{route('facturas.download2')}}" method="POST" class="form-inline float-right">
                                @csrf
                                <input type="text" name="oficina_id" id="oficinaIdFactura" style="display:none">
                                <input type="text" name="fecha" id="fechaFactura" style="display:none">
                                <button type="submit" class="btn btn-primary rounded-0">FACTURA  CLIENTES</button>
                            </form>
                            <form action="{{route('corte-caja.export.datos-fiscales')}}" method="GET"
                                class="form-inline">
                                @csrf
                                <input type="text" name="oficina_id" id="oficinaIdDatosFiscales" style="display:none">
                                <input type="text" name="fecha" id="fechaDatosFiscales" style="display:none">
                                <button type="submit" class="btn btn-primary rounded-0">DATOS FISCALES</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">

    $(document).on('change', '#oficina', function(){
        $('#oficinaIdFactura').val( $(this).val() );
        $('#oficinaIdDatosFiscales').val( $(this).val() );
    });

    $(document).on('change','#fecha',function(){
        $('#fechaFactura').val( $(this).val() );
        $('#fechaDatosFiscales').val( $(this).val() );
    });

    $(document).ready(function(){
        $('#paciente_id').change(function() {
                var id = $('#paciente_id').val();                
                //alert(id);
                $.ajax({
                    url: "{{ url('/ventas_from') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function(res){
                        $('#div_ventas').show();
                        $('#venta_id').html(res);
                    },
                    error: function (){
                        //$('#estados').html('');
                    }
                });

                $.ajax({
                    url:"{{url('/get_paciente')}}/"+id,
                    type:"GET",
                    dataType: "json",
                    success:function(res){
                        var paciente=res.paciente;
                        $('#nombre').prop('value',paciente.nombre+' '+paciente.paterno+' '+paciente.materno);
                        $('#correo').prop('value',paciente.mail);
                        $('#rfc').prop('value',paciente.rfc);
                    }
                });
            });

        $('#venta_id').change(function(){
            $('#formulario').show();
        });
    });
</script>

@endsection