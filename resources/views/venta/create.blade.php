@extends('principal')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@section('content')
{{-- {{ dd($productos) }} --}}


<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            {{$errors->first()}}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            {{-- CABECERA DE LA SECCIÓN --}}
            <div class="row">
                <div class="col-4">
                    <h4>Punto de venta</h4>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('ventas.index') }}" class="btn btn-primary">
                        <i class="fa fa-bars"></i><strong>Lista de ventas</strong>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-body">
                <form role="form" id="form-cliente" method="POST" action="{{ route('ventas.store') }}" name="form">
                    {{ csrf_field() }}
                    
                    <div class="row">
                        <div class="col-4 form-group">
                            <label class="control-label">Fitter:</label>
                            @if (Auth::user()->id == 1 || Auth::user()->empleado->puesto->nombre != "fitter")                            
                                <select name="empleado_id" id="" class="form-control" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($empleadosFitter as $empleadoFitter)
                                        <option value="{{$empleadoFitter->id}}">
                                            {{$empleadoFitter->nombre}} {{$empleadoFitter->appaterno}} {{$empleadoFitter->apmaterno}}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    {{-- TABLA DE PACIENTES --}}
                    @if (!isset($paciente))
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-header rounded-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6" >
                                            <h3>Pacientes</h3>
                                        </div>
                                        <div class="col-sm-12 col-md-6" >
                                            <label>Buscar:<input type="search" id="BuscarPaciente"  >
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body rounded-0">
                                    <div class="table-responsive">
                                        <table class="table" id="pacientes">
                                            <thead>
                                                <tr>
                                                    <th>RFC</th>
                                                    <th>Nombre</th>
                                                    <th>Apellidos</th>
                                                    <th>Teléfono</th>
                                                    <th>Seleccionar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- TABLA DE PRODUCTOS --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-header rounded-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6" >
                                            <h3>Productos</h3>
                                        </div>
                                        <div class="col-sm-12 col-md-6" >
                                            <label>Buscar:<input type="search" id="BuscarProducto"  >
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                    
                                <div class="card-body rounded-0">
                                    <div class="table-responsive">
                                            <table class="table" id="productos">
                                                    <thead>
                                                        <tr>
                                                            <th>SKU</th>
                                                            <th>UPC</th>
                                                            <th>swiss ID</th>
                                                            <th>Producto</th>
                                                            <th>Precio</th>
                                                            <th>Precio con iva</th>
                                                            <th>Agregar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    

                    {{-- DETALLES DE LA COMPRA --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-header">
                                    <h3>Detalles de la compra</h3>
                                </div>
                                {{-- TABLA DE PRODUCTOS SELECCIONADOS --}}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Cantidad</th>
                                                        <th>Producto</th>
                                                        <th>Precio Unitario</th>
                                                        <th>Precio Unitario + IVA</th>
                                                        <th>Subtotal</th>
                                                        <th>Quitar</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_productos">
                                                    {{-- <div id="tbody_productos"></div> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>

                                    {{-- PROMOCIONES Y DESCUENTOS --}}
                                    <div class="row">
                                            {{-- INPUT DESCUENTO --}}
                                            <div class="col-12 col-sm-6 col-md-4">
                                                    <label for="descuento_id" class="text-uppercase text-muted">Descuento</label>
                                                    <select class="form-control" name="descuento_id" id="descuento_id" >
                                                        <option value="">Selecciona...</option>
                                                        @foreach ($descuentos as $descuento)
                                                            <option value="{{$descuento->id}}">{{$descuento->nombre}}</option>
                                                        @endforeach
                                                    </select>                            
                                            </div>
                                            {{-- INPUT PROMOCIÓN --}}
                                            <div class="col-12 col-sm-6 col-md-4 form-group">
                                                <label for="promocion_id" class="text-uppercase text-muted">Promocion</label>
                                                <select class="form-control" name="promocion_id" id="promocion_id">
                                                    <option value="">Selecciona...</option>                               
                                                </select>
                                            </div>
                                        </div>
                                    {{-- Pagos Y tarjeta --}}
                                    <div class="row">
                                        {{-- INPUT Tipo de pago --}}
                                        <div class="col-12 col-sm-6 col-md-4">
                                                <label for="tipoPago" class="text-uppercase text-muted">Tipo de pago</label>
                                                <select class="form-control" name="tipoPago" id="tipoPago" >
                                                    <option value="0">Selecciona...</option>
                                                    <option value="1">Efectivo</option>
                                                    <option value="2">Tajeta</option>
                                                    <option value="3">Combinado</option>
                                                </select>                            
                                        </div>
                                        {{-- INPUT tarjeta --}}
                                        
                                        <div id="tar1" class="col-12 col-sm-6 col-md-4 form-group" style="display: none;">
                                            <label for="banco" class="text-uppercase text-muted">Banco</label>
                                            <select class="form-control" name="banco" id="banco">
                                                <option value="">Selecciona...</option> 
                                                <option value="SANTANDER">SANTANDER</option> 
                                                <option value="AMEX">AMEX</option>                          
                                            </select>
                                        </div>
                                        {{-- INPUT numeros de  tarjeta --}}
                                        <div id="tar2" class="col-12 col-sm-6 col-md-4 form-group" style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Ultimos 4 digitos de tarjeta</label>
                                            <input type="text" class="form-control" id="digitos_targeta" name="digitos_targeta" >
                                        </div>
                                        
                                    </div>
                                    {{-- P --}}
                                    <div class="row">
                                                                                {{-- INPUT numeros de  tarjeta --}}
                                        <div id="tar4" class="col-12 col-sm-6 col-md-4 form-group" style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Monto de pago en efectivo</label>
                                            <input type="text" class="form-control" id="PagoEfectivo" name="PagoEfectivo" >
                                        </div>
                                        <div id="tar5" class="col-12 col-sm-6 col-md-4 form-group" style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Monto de pago con tarjeta</label>
                                            <input type="text" class="form-control" id="PagoTarjeta" name="PagoTarjeta" >
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="paciente_id" id="paciente_id"  required>
                                    <div class="row">
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Paciente: </label>
                                            <input type="text" class="form-control" id="inputNombrePaciente" required readonly>
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Fecha: </label>
                                            <input type="date" name="fecha" class="form-control" readonly="" value="{{date('Y-m-d')}}"
                                                required="">
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Folio: </label>
                                            <input type="number" name="precio" class="form-control" readonly="" value="{{$folio}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- INPUT SIGPESOS GANADOS --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                                            
                                            <label for="" class="text-uppercase text-muted">Sigpesos ganados: </label>
                                            
                                            <input type="number" class="form-control" name="sigpesos" id="sigpesos" value="0" min="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT SIGPESOS A USAR --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                                            
                                            <label for="" class="text-uppercase text-muted">Sigpesos a usar: </label>
                                            
                                            <input type="number" class="form-control" name="sigpesos_usar" id="sigpesos_usar" value="0" min="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT SUBTOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                                            
                                            <label for="" class="text-uppercase text-muted">Subtotal: $</label>
                                            
                                            <input type="number" required="" class="form-control" name="subtotal" id="subtotal" value="0" min="1" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT DESCUENTO --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                                            
                                            <label for="" class="text-uppercase text-muted">Descuento: $</label>
                                            
                                            <input type="number" required="" class="form-control" name="descuento" id="descuento" value="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT IVA --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                                            
                                            <label for="" class="text-uppercase text-muted">Iva: $</label>
                                            
                                            <input type="number" required="" class="form-control" name="iva" id="iva" value="0" min="1" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT TOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">
                                            
                                            <label for="" class="text-uppercase text-muted">Total: $ </label>
                                            
                                            <input type="number" required="" class="form-control" name="total" id="total" value="0" min="1" step="0.01" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- BOTON GUARDAR --}}
                    <div class="row">
                        <div class="col-12">
                            <button type="submit"  class="btn btn-success rounded-0">
                                <i class="fa fa-check"></i> Finalizar comprar
                            </button>
                        </div>
                    </div>

                    </div>



                    <div class="card-footer">
                        <div class="row">
                            <div class="col-4 text-right text-danger">
                                ✱Campos Requeridos.
                            </div>
                        </div>
                    </div>
                    
                </form>
            <div class="col-4 offset-4 text-center">
{{--                 <form action="{{ route('pembayaran.print') }}" method="POST">                
                    <input type="hidden" name="_token" class="form-control" value="{!! csrf_token() !!}"> --}}
                    {{-- <button type="submit" name="submit" class="btn btn-info">Imprimir</button> --}}
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>
<script>
    function agregarProducto(p){
        let producto = JSON.parse($(p).val());
        // alert(producto);
        $('#tbody_productos')
        .append(`
        <tr id="producto_agregado${producto.id}">
            <td>

                <input class="form-control cantidad" min="1" onchange="cambiarTotal(this, '#producto_agregado${producto.id}')" type="number" name="cantidad[]" value="1">
                <input class="form-control" type="hidden" name="producto_id[]" value="${producto.id}">

            </td>
            <td>
                ${producto.descripcion}
            </td>
            <td class="precio_individual">
                ${producto.precio_publico}
            </td>
            <td>${producto.precio_publico_iva}</td>
            <td class="precio_total">
                ${producto.precio_publico}
            </td>
            <td>
                <button onclick="quitarProducto('#producto_agregado${producto.id}')" type="button" class="btn btn-danger boton_quitar">
                    <i class="fas fa-minus"></i>
                </button>
            </td>
        </tr>`);
        cambiarTotalVenta();
    }

    function quitarProducto(p){
        $(p).remove();
        cambiarTotalVenta();
    }

    function cambiarTotalVenta(){
        let precios_total = $('td.precio_total').toArray();
        let total = 0;
        precios_total.forEach(e => {
            total += parseFloat(e.innerText);
            console.log(total);
        });
        $('#promocion_id option:eq(0)').prop('selected',true);
        $('#descuento').val(0);
        $('#sigpesos').val(0);
        $('#subtotal').val(total.toFixed(2));
        let getIva = ($('#subtotal').val()*0.16);
        $('#iva').val(getIva.toFixed(2));
        //console.log(getIva.toFixed(2));
        if ($('#sigpesos_usar').val()==null) {
             $.ajax({
                url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
                type:'GET',
                success: function(res){
                    if (!isNaN(res)&&res!="") {
                        var sigpesos=$('#sigpesos_usar').val(parseInt(res));
                    console.log('sigpesos peticione4444',res);
                }else{             
                    res=0;       
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res));
                    console.log('sigpesos peticion5555',res);
                }
                }

            });
             console.log('sigpesos3rff', sigpesos);
        }
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var subtotal=parseFloat($('#subtotal').val())
        var iva=parseFloat($('#iva').val())
        var des=parseFloat($('#descuento').val());
        // console.log(des);
        console.log('SUBTOTAL', subtotal);
        console.log('iva', iva);
        console.log('des', des);
        console.log('sigpesos', sigpesos);  
        console.log('TOTAL ACTUALIZADO',subtotal+iva-des-sigpesos);
        var aux=subtotal+iva-des-sigpesos;
        $('#total').val(aux.toFixed(2));
        // $('#total').val('ola');
    }

    function cambiarTotal(a, p){
        let cant = parseFloat(a.value);
        let ind = parseFloat($(p).find('.precio_individual').first().text());
        let total = cant*ind;
        $(p).find('.precio_total').text(total);
        cambiarTotalVenta();
    }

    $(document).ready(function () {
        
        $('#tipoPago').change(function(){  
            console.log('Entra');
            if ($('#tipoPago').val()==2){
                $('#tar1').show();
                $('#tar2').show();
                $('#tar5').show();
                $('#tar4').hide();
                $('#digitos_targeta').required;

            }else if ($('#tipoPago').val()==3) {
                $('#tar1').show();
                $('#tar2').show();
                $('#tar4').show();
                $('#tar5').show();
                $('#digitos_targeta').required;
            }else if ($('#tipoPago').val()==1) {
               $('#banco').val(null);
                $('#digitos_targeta').val(null);
                $('#tar1').hide();
                $('#tar2').hide();
                $('#tar4').show();
                $('#tar5').hide();
            }else{
                $('#banco').val(null);
                $('#digitos_targeta').val(null);
                $('#tar1').hide();
                $('#tar2').hide();
                $('#tar4').hide();
                $('#tar5').hide();
            }

        });
        /*$('#pacientes').DataTable({
            pageLength : 3,
            'language':{
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Productos _START_ al _END_ de un total de _TOTAL_ ",
                "sInfoEmpty":      "Productos 0 de un total de 0 ",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });*/
    });
</script>

<script type="text/javascript">



    $(document).ready(function(){

        $('#descuento_id').change(function(){            
            var id=$('#descuento_id').val();
            $('#descuento').val(0);
            $('#sigpesos').val(0);
            var subtotal=parseFloat($('#subtotal').val());
            var iva=parseFloat($('#iva').val());
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var aux=subtotal+iva-des-sigpesos;
            $('#total').val(aux.toFixed(2));
            $.ajax({
                url:"{{ url('/get_promos') }}/"+id,
                type:'GET',
                dataType:'html',
                success: function(res){
                    $('#promocion_id').html(res);

                }
            });
        });

        $('#promocion_id').change(function(){
            var id=$('#promocion_id').val();

            // SI NO HAY PROMOCION QUITAMOS EL DESCUENTO
            if(!id)
            {
                $('#descuento').val(0);
                $('#sigpesos').val(0);
            }

            // OBTENEMOS DATOS DE LA COMPRA
            var paciente_id=$('#paciente_id').val();
            var total_productos=parseInt(0);
            var subtotal=parseFloat($('#subtotal').val());
            var iva=parseFloat($('#iva').val());
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var aux=subtotal+iva-des-sigpesos;
            $('#total').val(aux.toFixed(2));
            var productos_id=[];
            var cantidad_id=[];

            // OBTENEMOS LA SUMA DE TODAS LAS CANTIDADES DE TODOS PRODUCTOS
            $('[name="cantidad[]"]').each(function(){
                total_productos+=parseInt($(this).val());
                cantidad_id.push($(this).val());
            });

            // OBTENEMOS EL ID DE LOS PRODUCTOS DE LA POSIBLE COMPRA
            $('[name="producto_id[]"]').each(function(){
                productos_id.push($(this).val());
            }); 
            $.ajax({
                url:"{{ url('/calcular_descuento') }}/"+id,
                type:'POST',
                data: {"_token": "{{CSRF_TOKEN()}}",
                    "subtotal":$("#subtotal").val(),
                    "paciente_id":paciente_id,
                    "total_productos":total_productos,
                    "productos_id":productos_id,
                    "cantidad_id":cantidad_id
                },
                dataType:'json',
                success: function(res){
                    // alert(res);                  
                    if(res.status){                       
                        $('#descuento').val(res.total);
                        $('#sigpesos').val(res.sigpesos);
                        des=parseFloat($('#descuento').val());
                        var aux=subtotal+iva-des-sigpesos;
                        $('#total').val(aux.toFixed(2));
                        if($('#total').val()<0)
                        {
                            $('#total').val(0);
                        }
                        //$('#total').val()
                    }
                    else
                    {
                        swal("No aplica el descuento");
                        $('#promocion_id option:eq(0)').prop('selected',true);
                    }
                },
                error: function(e){
                    alert('Error');
                    console.log(e);
                    
                }

            });
        });
       
        $('#paciente_id').change( async function(){
            var pacienteId=$(this).val();
            
            
        });
        
        $('#BuscarPaciente').change( function() {
            $("#pacientes").dataTable().fnDestroy();
            //console.log($(this).val());
            $('#pacientes').DataTable({
                "ajax":{
                    type: "POST",
                    url:"/getPacientes_nombre",
                    data: {"_token": $("meta[name='csrf-token']").attr("content"),
                           "nombre" : $(this).val()
                    }
                },
                "searching": false,
                pageLength : 3,
                'language':{
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Productos _START_ al _END_ de un total de _TOTAL_ ",
                    "sInfoEmpty":      "Productos 0 de un total de 0 ",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",

                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });

        $('#BuscarProducto').change( function() {
            $("#productos").dataTable().fnDestroy();
           //console.log($(this).val());
            $('#productos').DataTable({
                "ajax":{
                    type: "POST",
                    url:"getProductos_nombre",
                    data: {"_token": $("meta[name='csrf-token']").attr("content"),
                           "nombre" : $(this).val()
                    }
                },
                "searching": false,
                pageLength : 3,
                'language':{
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Productos _START_ al _END_ de un total de _TOTAL_ ",
                    "sInfoEmpty":      "Productos 0 de un total de 0 ",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",

                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });
    });

   @if(!isset($paciente))
    $(document).on('click', '.botonSeleccionCliente', async function(){
        
        const pacienteId = $(this).attr('pacienteId');

        const nombrePaciente = $(`.nombrePaciente[pacienteId=${pacienteId}]`).html();
        const apellidosPaciente = $(`.apellidosPaciente[pacienteId=${pacienteId}]`).html();

        console.log('datosPAciente: ',nombrePaciente,apellidosPaciente);

        $('#inputNombrePaciente').val( nombrePaciente + " " + apellidosPaciente );

        $('#paciente_id').val(pacienteId);
        console.log( 'Cliente seleccionado: ', pacienteId );
        $('#promocion_id option:eq(0)').prop('selected',true);
        $('#descuento').val(0);
        $('#sigpesos').val(0);
        var subtotal=parseFloat($('#subtotal').val());
        var iva=parseFloat($('#iva').val());
        var des=parseFloat($('#descuento').val());
        await $.ajax({
            url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
            type:'GET',
            success: function(res){
                if (!isNaN(res)&&res!="") {
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res));
                    console.log('sigpesos peticion00',res);
                }else{             
                    res=0;       
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res));
                    console.log('sigpesos peticion111',res);
                }
            }

        });

        if((subtotal+iva-des)<$('#sigpesos_usar').val())
        {
            $('#total').val(0);
        }
        else
        {
            var aux=subtotal+iva-des-$('#sigpesos_usar').val();
            $('#total').val(aux.toFixed(2));
            console.log('total',$('#sigpesos_usar').val())
        }
    });
   @endif
    $(document).on('change', '#paciente_id', function(){
        const pacienteId = $(this).val();
        console.log('aqui');
    });

</script>
@if(isset($paciente))

<script type="text/javascript">

   
    $(document).ready(function(){

        
        const pacienteId = {{$paciente->id}};

        const nombrePaciente = "{{ $paciente->nombre }}";
        const apellidosPaciente = "{{ $paciente->paterno.' '.$paciente->materno }}";

        console.log('datosPAciente: ',nombrePaciente,apellidosPaciente);

        $('#inputNombrePaciente').val( nombrePaciente + " " + apellidosPaciente );

        $('#paciente_id').val(pacienteId);
        console.log( 'Cliente seleccionado: ', pacienteId );
        $('#promocion_id option:eq(0)').prop('selected',true);
        $('#descuento').val(0);
        $('#sigpesos').val(0);
        var subtotal=parseFloat($('#subtotal').val());
        var iva=parseFloat($('#iva').val());
        var des=parseFloat($('#descuento').val());
         $.ajax({
            url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
            type:'GET',
            success: function(res){             
                if (!isNaN(res)&&res!="") {
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res));
                    console.log('sigpesos peticion00',res);
                }else{             
                    res=0;       
                    var sigpesos=$('#sigpesos_usar').val(0);
                    console.log('sigpesos peticion111',0);
                }
            }

        });

        if((subtotal+iva-des)<$('#sigpesos_usar').val())
        {
            $('#total').val(0);
        }
        else
        {
            var aux=subtotal+iva-des-$('#sigpesos_usar').val();
            $('#total').val(aux.toFixed(2));
            console.log('total',$('#sigpesos_usar').val())
        }
    });
   

</script>
@endif
@endsection