@extends('principal')
@section('content')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" defer></script>
<div class="container mt-5">
    
    @if ($errors->first())
        <div class="alert alert-danger">
            {{$errors->first()}}
        </div>
    @endif

    <input id="submenu" type="hidden" name="submenu" value="nav-crm">
    <div class="card">
        <div class="card-header ">
            <div class="row">
                <div class="col-4">
                    <h4>C.R.M.</h4>
                </div>
                <div class="col">
                    <button id="crear_crm_boton" type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#crear_crm_modal">
                        <strong>Crear <i class="fa fa-plus"></i></strong>
                    </button>
                
                    <div class="modal fade bd-example-modal-lg" id="historial_crm_modal" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4> Historial CRM</h4>
                                </div>
                                <div class="modal-body" >
                                   
                                        
                                    
                                </div>
                                <div class="modal-footer">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="crear_crm" name="crear_crm" action="{{route('crm.store')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="modal fade bd-example-modal-lg" id="crear_crm_modal" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4> Crear CRM</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                                <div class="form-group col-4 offset-4">
                                                        <label for="actual">✱Paciente</label>
                                                        <select class="form-control" name="paciente_id" id="paciente_id" required="">
                                                            <option value="">Selecciona paciente...</option>
                                                            @foreach($pacientes as $paciente)
                                                            <option value="{{$paciente->id}}">{{$paciente->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-4">
                                                <label for="actual">✱Fecha actual</label>
                                                <input type="date" class="form-control" value="{{date('Y-m-d')}}"
                                                    readonly="">
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="fecha_aviso">✱Fecha aviso</label>
                                                <input type="date" class="form-control" name="fecha_aviso" required="">
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="fecha_contacto">✱Fecha contacto</label>
                                                <input type="date" class="form-control" name="fecha_contacto"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-4">
                                                <label for="forma_contacto">✱Forma de contacto</label>
                                                <select class="form-control" name="forma_contacto" required="">
                                                    <option value="">Seleccionar</option>
                                                    <option value="Telefono">Telefono</option>
                                                    <option value="Mail">Mail</option>
                                                    <option value="Celular">Celular</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="estado">✱Estado</label>
                                                <select class="form-control" name="estado_id" required="">
                                                <option value="">Seleccionar</option>
                                                    @foreach($estados as $estado)
                                                    <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="hora">✱Hora</label>
                                                <input class="form-control" type="text" name="hora" required="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-4">
                                                <label for="observaciones">Observaciones</label>
                                                <textarea class="form-control" name="observaciones"></textarea>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="acuerdos">Acuerdos</label>
                                                <textarea class="form-control" name="acuerdos"></textarea>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="comentarios">Comentarios</label>
                                                <textarea class="form-control" name="comentarios"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" form="crear_crm" class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="ver_crear_crm" name="ver_crear_crm" action="{{route('crm.store')}}" method="POST">
                    <div class="modal fade bd-example-modal-lg" id="ver_crm_modal" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                         
                        {{ csrf_field() }}
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>
                                        Ver C.R.M.
                                    </h4>
                                </div>
                                
                                <div class="modal-body">
                                    <div class="form-row">
                                        
                                         
                                    </div>
                                <div class="form-row">
                                            <div class="form-group col-4 offset-4">
                                                    <label for="actual">Paciente</label>
                                                    <input type="text" class="form-control" 
                                                name="nombre" value="" readonly="" id="nombre"> 
                                                    
                                                    <input type="text" class="paciente_id form-control" id="paciente_id"
                                                name="paciente_id" value="" readonly="" style="display: none;">
                                                </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <label for="actual">Fecha actual</label>
                                            <input type="date" class="form-control" id="actual" value="{{date('Y-m-d')}}" readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="fecha_aviso">Fecha aviso</label>
                                            <input type="date" class="form-control" id="fecha_aviso" name="fecha_aviso"
                                                value="" readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="fecha_contacto">Fecha contacto</label>
                                            <input type="date" class="form-control" id="fecha_contacto"
                                                name="fecha_contacto" value="" readonly="">
                                        </div>
                                    </div>
                                   
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <label for="forma_contacto">Forma de contacto</label>
                                            <input type="text" class="form-control" id="forma_contacto"
                                                name="forma_contacto" value="" readonly="">

                                            <select class="form-control"  id="forma_contacto2" name="forma_contacto2"  style="display: none;" required="">
                                                    <option value="">Seleccionar</option>
                                                    <option value="Telefono">Telefono</option>
                                                    <option value="Mail">Mail</option>
                                                    <option value="Celular">Celular</option>
                                                </select>
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="estado">Estado</label>
                                            <input type="text" class="form-control" id="estado_id" name="estado_id" value="" style="display: none;" readonly="">
                                            <input type="text" class="form-control" id="estado" name="estado" value="" readonly="">
                                            <select class="form-control" id="estados" name="estados" required="" style="display: none;">
                                            <option value="">Seleccionar</option>
                                                @foreach($estados as $estado)
                                                <option value="{{$estado->id}}" onclick="EstadoSelect({{$estado->id}})">{{$estado->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="hora">Hora</label>
                                            <input class="form-control" type="text" id="hora" name="hora" value=""
                                                readonly="">
                                        </div>
                                    </div>
                                     <div class="form-row">
                                        <div class="form-group col-4">
                                            <label for="telefono">Telefono</label>
                                            <input type="text" class="form-control" id="telefono" name="telefono" value="" readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="celular">Celular</label>
                                            <input type="text" class="form-control" id="celular" name="celular"
                                                value="" readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="mail">Email</label>
                                            <input type="text" class="form-control" id="mail"
                                                name="mail" value="" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <label for="observaciones">Observaciones</label>
                                            <textarea class="form-control" id="observaciones" name="observaciones"
                                                value="" readonly=""></textarea>
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="acuerdos">Acuerdos</label>
                                            <textarea class="form-control" id="acuerdos" name="acuerdos" value=""
                                                readonly=""></textarea>
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="comentarios">Comentarios</label>
                                            <textarea class="form-control" id="comentarios" name="comentarios" value=""
                                                readonly=""></textarea>


                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-sm-2 col-sm-offset-5 text-center">
                                        <button name="vinculo" id="vinculo" class="btn btn-block btn-success">Crear Nuevo</button>
                                        <button type="submit"  id="guardar_crm" form="ver_crear_crm" style="display: none;" class="btn btn-success">Guardar</button>
                                        
                                        
                                    </div>
                                    <div class="col-sm-2 col-sm-offset-5 text-center">
                                    <a  form="" name="cerrar_ver_crm_modal" id="cerrar_ver_crm_modal" style="display: none;" class="btn btn-block btn-danger" href="{{route('crm.index')}}" >Cancelar</a>
                                    {{-- <button type="submit" form="ver_crm" id="cerrar_ver_crm_modal"
                                        class="btn btn-danger">Cerrar</button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div class="card-body">
        
            <form action="{{route('crm.index')}}" method="GET" id="formBusuqeda">
                @csrf
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="desde" class="text-muted text-uppercase"><strong>Desde:</strong></label>
                        <input type="date" class="form-control" name="fechaInicioBusqueda" required>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="hasta" class="text-muted text-uppercase"><strong>Hasta:</strong></label>
                        <input type="date" class="form-control" name="fechaFinBusqueda" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <a type="submit" class="btn btn-primary text-white border-0" id="botonBuscarCrms">Buscar</a>
                    </div>
                </div>
            </form>

            

            <br>

            <table class="table table-responsive-md table-bordered table-hover" id="tableUser2" >
                <thead>
                    <tr class="info">
                        <th>Paciente</th>
                        <th>Creación</th>
                        <th>Fecha Aviso</th>
                        <th>Fecha Contacto</th>
                        <th>Ultima Venta</th>
                        <th>Historial de Paciente</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @if(empty($crms))
                    <h5>No hay ningún crm registrado</h5>
                    @else
                    @foreach($crms as $crm)
                        @if($UltimaVenta=$ventas->where('paciente_id',$crm->paciente_id)->last())
                       
                        <tr class="active tupla" >
                            <td title="Has Click Aquì para ver o modificar" style="cursor: pointer"  id="crear_crm_boton"  data-toggle="modal" data-target="#ver_crm_modal" onclick="mostrarCrm('{{$crm}}','{{ $pacientes->find($crm->paciente_id) }}','{{ $estados->find($crm->estado_id) }}')">{{$crm->paciente['nombre']}}</td>
                            <td title="Has Click Aquì para ver o modificar" style="cursor: pointer"  id="crear_crm_boton"  data-toggle="modal" data-target="#ver_crm_modal" onclick="mostrarCrm('{{$crm}}','{{ $pacientes->find($crm->paciente_id) }}','{{ $estados->find($crm->estado_id) }}')">{{$crm->created_at}}</td>
                            <td title="Has Click Aquì para ver o modificar" style="cursor: pointer"  id="crear_crm_boton"  data-toggle="modal" data-target="#ver_crm_modal" onclick="mostrarCrm('{{$crm}}','{{ $pacientes->find($crm->paciente_id) }}','{{ $estados->find($crm->estado_id) }}')">{{$crm->fecha_aviso}}</td>
                            <td title="Has Click Aquì para ver o modificar" style="cursor: pointer"  id="crear_crm_boton"  data-toggle="modal" data-target="#ver_crm_modal" onclick="mostrarCrm('{{$crm}}','{{ $pacientes->find($crm->paciente_id) }}','{{ $estados->find($crm->estado_id) }}')">{{$crm->fecha_contacto}}</td>
                            <td title="Has Click Aquì para ver o modificar" style="cursor: pointer"  id="crear_crm_boton"  data-toggle="modal" data-target="#ver_crm_modal" onclick="mostrarCrm('{{$crm}}','{{ $pacientes->find($crm->paciente_id) }}','{{ $estados->find($crm->estado_id) }}')">{{$UltimaVenta->fecha}}</td>
                            
                            {{--<td>
                                    <a  class="btn btn-primary" onclick="generarHistorialVentas('{{ $pacientes->find($crm->paciente_id) }}')">
                                    <strong>Ver Historial de ventas</strong>
                                </a>
                                <button id="crear_crm_boton" type="button" class="btn btn-success" data-toggle="modal"
                            data-target="#crear_crm_modal">
                            <strong>Crear <i class="fa fa-plus"></i></strong>
                        </button>
                                 <button id="crear_crm_boton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#ver_crm_modal"onclick="mostrarCrm('{{$crm}}')">
                                    <button type="button" onclick="mostrarCrm('{{$crm}}')" class="btn btn-primary botonMostrarCrm">Ver</button> 
                            </td>--}}
                            <td class="text-center">
                                <button id="crear_crm_boton" type="button" class="btn btn-success" data-toggle="modal" onclick="generarHistorial('{{ $pacientes->find($crm->paciente_id) }}')">
                                    <strong>Ver Historial </strong>
                                </button>    
                                {{-- <button id="crear_crm_boton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#ver_crm_modal"onclick="mostrarCrm('{{$crm}}')">
                                    <button type="button" onclick="mostrarCrm('{{$crm}}')" class="btn btn-primary botonMostrarCrm">Ver</button> --}}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    @endif
                    
                </tbody>
            </table>
            {{ $crms->links() }}
             
             <div class="tablaUsuario_Ventas" id="tablaUsuario_Ventas" style="display: none;" >
                 <h4>Historial Ventas</h4>
                <table class="table table-striped table-bordered table-hover" id="tablaPacientesV">
                    <thead>
                    <tr class="info">
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Productos</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                    <tbody id="datostablasven">

                    </tbody>
                </table>
            </div>  
             <div class="tablaUsuario_Crm" id="tablaUsuario_Crm" style="display: none;" >
                 <h4>C.R.M. Historial</h4>
                <table class="table table-striped table-bordered table-hover" id="tablaPacientes">
                    <thead>
                    <tr class="info">
                        <th>Nombre</th>
                        <th>Creación</th>
                        <th>Fecha Aviso</th>
                        <th>Fecha Contacto</th>
                        <th>Forma Contacto</th>
                        <th>Estado</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                    <tbody id="datostablas">

                    </tbody>
                </table>
            </div> 
        </div>
        <div class="card-footer">
  
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('#cerrar_ver_crm_modal').click(function () {
            $('#ver_crm_modal').modal('hide');
            
        });
        $('#vinculo').click(function() {
            
            $('#observaciones').val("");
            $('#acuerdos').val("");
            $('#comentarios').val("");
            $('#fecha_aviso').val("");
            $('#fecha_contacto').val("");
            $('#observaciones').attr('readonly', false);
            $('#acuerdos').attr('readonly', false);
            $('#comentarios').attr('readonly', false);
            $('#fecha_aviso').attr('readonly', false);
            $('#fecha_contacto').attr('readonly', false);
            $('#hora').attr('readonly', false);
            $('#forma_contacto').hide(100);
            $('#vinculo').hide(100);
            //$('#estado').hide(100);
            $('#forma_contacto2').show();
            //$('#estado2').show();
            $('#guardar_crm').show();
            $('#cerrar_ver_crm_modal').show();
            $('#hora').val("");
            $('#estados').show();
            $('#estado').hide(100);

            //$('#estado').removeAttr('disabled');
            $('#observaciones').attr('required', true);
            $('#acuerdos').attr('required', true);
            $('#comentarios').attr('required', true);
            $('#fecha_aviso').attr('required', true);
            $('#fecha_contacto').attr('required', true);
            $('#hora').attr('required', true);
            
        });
        $('#estados').change(function() {
            $('#estado_id').val($(this).val());
            console.log($('#estado_id').val());
        });
       
    });
    
    function EstadoSelect(id){
        

    }
    function  mostrarCrmHistorial(id_crm,nomEstado){
        var fecha = new Date(); //Fecha actual
        $.ajax({
             type: "POST",
            data: {"_token": $("meta[name='csrf-token']").attr("content"),
                           "id" : id_crm
            },
            url:"crm_especifico",

            success: function (data) {
                    var valores = JSON.parse(data);
                    var paciente =valores[1];
                    var crm=valores[0];
                    //console.log(data->id);
                    $('.paciente_id').val(paciente.id);
                    $('#nombre').val(paciente.nombre);
                    $('#telefono').val(paciente.telefono);
                    $('#celular').val(paciente.celular);
                    $('#mail').val(paciente.mail);
                    $('#observaciones').val(crm.observaciones);
                    $('#acuerdos').val(crm.acuerdos);
                    $('#comentarios').val(crm.comentarios);
                    $('#fecha_aviso').val(crm.fecha_aviso);
                    $('#fecha_contacto').val(crm.fecha_contacto);
                    $('#forma_contacto').val(crm.forma_contacto);
                    $('#estado').val(nomEstado);
                    $('#estado_id').val(crm.estado_id);
                    $('#hora').val(crm.hora);
                    $('#forma_contacto2').hide(100);
                    $('#forma_contacto').show();
                    $('#observaciones').attr('readonly', true);
                    $('#acuerdos').attr('readonly', true);
                    $('#comentarios').attr('readonly', true);
                    $('#fecha_aviso').attr('readonly', true);
                    $('#fecha_contacto').attr('readonly', true);
                    $('#hora').attr('readonly', true);
                    //$('#telefono').val(1111111111);
                    //$('#celular').val(paciente.celular);
                    //$('#mail').val(paciente.mail);
                    $('#guardar_crm').hide(100);
                    $('#cerrar_ver_crm_modal').hide(100);
                    $('#vinculo').show();
            }
        
    });
    }

    function mostrarCrm(data,data2,data3) {
        
        var fecha = new Date(); //Fecha actual
        var crm = JSON.parse(data);
        var paciente = JSON.parse(data2);
        var estado = JSON.parse(data3);
        
        //$('#estado_id').show();
        $('.paciente_id').val(paciente.id);
        $('#nombre').val(paciente.nombre);
        $('#telefono').val(paciente.telefono);
        $('#celular').val(paciente.celular);
        $('#mail').val(paciente.mail);
        $('#observaciones').val(crm.observaciones);
        $('#acuerdos').val(crm.acuerdos);
        $('#comentarios').val(crm.comentarios);
        $('#fecha_aviso').val(crm.fecha_aviso);
        $('#fecha_contacto').val(crm.fecha_contacto);
        $('#forma_contacto').val(crm.forma_contacto);
        $('#estado').val(estado.nombre);
        $('#hora').val(crm.hora);
        $('#forma_contacto2').hide(100);
        $('#forma_contacto').show();
        $('#estados').hide(100);
        $('#estado').show();

        $('#estado_id').val(estado.id);
         $('#observaciones').attr('readonly', true);
        $('#acuerdos').attr('readonly', true);
        $('#comentarios').attr('readonly', true);
        $('#fecha_aviso').attr('readonly', true);
        $('#fecha_contacto').attr('readonly', true);
        $('#hora').attr('readonly', true);
        //$('#telefono').val(1111111111);
        //$('#celular').val(paciente.celular);
        //$('#mail').val(paciente.mail);
        $('#guardar_crm').hide(100);
        $('#cerrar_ver_crm_modal').hide(100);
        $('#vinculo').show();
        // $('#ver_crm_modal').modal('show');
       // $('#cerrar_ver_crm_modal').click(function(){
       //     alert("uihsdiuhfidoshoidjf");
        //});
    }

$("#botonBuscarCrms").click(function(){
    $("#formBusuqeda").submit();
});

$(document).on('click', '.botonMostrarCrm', function(){
    $('#ver_crm_modal').modal('show');
});

    function generarHistorial(data1) {
      // Obtener la referencia del elemento body
      // Crea un elemento <table> y un elemento <tbody>
      //var crm = JSON.parse(data3);
      $('#tablaUsuario_Crm').show();
      var paciente = JSON.parse(data1);
      // Obtener la referencia del elemento body
     $.ajax({
        type: "POST",
        data: {"_token": $("meta[name='csrf-token']").attr("content"),
                       "id" : paciente.id,
                       "nombre": paciente.nombre
        },
        url:"getTabla_modalidad",

        success: function (data) {
                $("#datostablas").html(data);
        }
    });
     $('#tablaUsuario_Ventas').show();
      var paciente = JSON.parse(data1);
      // Obtener la referencia del elemento body
     $.ajax({
        type: "POST",
        data: {"_token": $("meta[name='csrf-token']").attr("content"),
                       "id" : paciente.id,
                       "nombre": paciente.nombre
        },
        url:"getTabla_modalidad_ventas",

        success: function (data) {
                $("#datostablasven").html(data);
        }
    });
 }

    function generarHistorialVentas(data1) {
      // Obtener la referencia del elemento body
      // Crea un elemento <table> y un elemento <tbody>
      //var crm = JSON.parse(data3);
      $('#tablaUsuario_Ventas').show();
      var paciente = JSON.parse(data1);
      // Obtener la referencia del elemento body
     $.ajax({
        type: "POST",
        data: {"_token": $("meta[name='csrf-token']").attr("content"),
                       "id" : paciente.id,
                       "nombre": paciente.nombre
        },
        url:"getTabla_modalidad_ventas",

        success: function (data) {
                $("#datostablasven").html(data);
        }
    });

      /* $('#tablaPacientes').DataTable({
            "ajax":{
                type: "POST",
                url:"getTabla_modalidad",
                data: {"_token": $("meta[name='csrf-token']").attr("content"),
                       "id" : paciente.id,
                       "nombre": paciente.nombre
        },
            },
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
   
 
    }
    $(document).ready(function () {
        $('#tableUser2').DataTable({
            pageLength : 10,
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
</script>

<script type="text/javascript">
    $('document').ready(function () {
        $('#managephoto').click(function () {
                $('#myModal').modal('show');
        });
    });
</script>

@endsection
