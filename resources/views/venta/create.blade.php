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
                    <input type="hidden" name="oficina_id" value="{{session('oficina')}}">
                    <input type="hidden" name="cumpleDes" id="cumpleDes" value="0">
                    <div class="row">
                        <div class="col-4 form-group">
                            <label class="control-label">Fitter:</label>
                            @if (Auth::user()->id == 1)
                                <select name="empleado_id" id="empleado_id" class="form-control" required>
                                    <option value="">Seleccionar</option>
                                    @foreach ($empleadosFitter as $empleadoFitter)
                                    <option value="{{$empleadoFitter->id}}">
                                        {{$empleadoFitter->nombre}} {{$empleadoFitter->appaterno}}
                                        {{$empleadoFitter->apmaterno}}
                                    </option>
                                    @endforeach
                                </select>
                            @else
                                @if (Auth::user()->empleado->datosLaborales()->orderBy('id', 'desc')->first()!==null)
                                    @if (Auth::user()->id == 1 || Auth::user()->empleado->puesto->nombre != "fitter")
                                    <select name="empleado_id" id="empleado_id" class="form-control" required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($empleadosFitter as $empleadoFitter)
                                        <option value="{{$empleadoFitter->id}}">
                                            {{$empleadoFitter->nombre}} {{$empleadoFitter->appaterno}}
                                            {{$empleadoFitter->apmaterno}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="text" class="form-control" id="empleado_id" required readonly
                                        value="{{Auth::user()->empleado->id}}" style="display: none;">
                                    <input type="text" class="form-control" required readonly
                                        value=" {{Auth::user()->empleado->nombre}} {{Auth::user()->empleado->appaterno}} {{Auth::user()->empleado->apmaterno}}">
                                    @endif
                                @else
                                    <select name="empleado_id" id="empleado_id" class="form-control" required>
                                        <option value="">Seleccionar</option>
                                        @foreach ($empleadosFitter as $empleadoFitter)
                                        <option value="{{$empleadoFitter->id}}">
                                            {{$empleadoFitter->nombre}} {{$empleadoFitter->appaterno}}
                                            {{$empleadoFitter->apmaterno}}
                                        </option>
                                        @endforeach
                                    </select>
                                @endif
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
                                        <div class="col-sm-12 col-md-6">
                                            <h3>Pacientes</h3>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label>Buscar:<input type="search" id="BuscarPaciente" onkeypress="return event.keyCode!=13">
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
                                        <div class="col-sm-12 col-md-6">
                                            <h3>Productos</h3>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label>Buscar:<input type="text" id="BuscarProducto" onkeypress="return event.keyCode!=13">
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
                                    <div class="row"id="PromocionDescuento" >
                                        {{-- INPUT DESCUENTO --}}
                                        <div class="col-12 col-sm-6 col-md-4 form-group">
                                            <label for="descuento_id"
                                                class="text-uppercase text-muted">Descuento</label>
                                            <select class="form-control" name="descuento_id" id="descuento_id">
                                                <option value="">Selecciona...</option>
                                                @foreach ($descuentos as $descuento)
                                                <option value="{{$descuento->id}}">{{$descuento->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- INPUT PROMOCIÓN --}}
                                        <div class="col-12 col-sm-6 col-md-4 form-group">
                                            <label for="promocion_id"
                                                class="text-uppercase text-muted">Descripción</label>
                                            <select class="form-control" name="promocion_id" id="promocion_id">
                                                <option value="">Selecciona...</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 text-center">
                                            <div class="alert alert-danger" id="ErrorInapam" style="display: none;">
                                                El INAPAM no esta cargado 
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="INAPAM">
                                                <label class="form-check-label" for="exampleCheck1">INAPAM</label>
                                            </div>
                                       </div>
                                    </div>
                                    
                                    {{-- Pagos Y tarjeta --}}
                                    <div class="row">
                                        {{-- INPUT Tipo de pago --}}
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <label for="tipoPago" class="text-uppercase text-muted">Tipo de pago</label>
                                            <select class="form-control" name="tipoPago" id="tipoPago">
                                                <option value="0">Selecciona...</option>
                                                <option value="1">Efectivo</option>
                                                <option value="2">Tarjeta</option>
                                                <option value="3">Combinado</option>
                                                <option value="4">Sigpesos</option>
                                            </select>
                                        </div>
                                        {{-- INPUT tarjeta --}}

                                        <div id="tar1" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="banco" class="text-uppercase text-muted">Banco</label>
                                            <select class="form-control" name="banco" id="banco">
                                                @foreach ($Bancos as $Banco)
                                                <option value="{{$Banco->nombre}}">{{$Banco->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- INPUT numeros de  tarjeta --}}
                                        <div id="tar2" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Ultimos 4 digitos de
                                                tarjeta</label>
                                            <input type="text" class="form-control" id="digitos_targeta"
                                                name="digitos_targeta">
                                        </div>

                                    </div>
                                    {{-- P --}}
                                    <div class="row">
                                        {{-- INPUT numeros de  tarjeta --}}
                                        <div id="tar4" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Monto de pago en
                                                efectivo</label>
                                            <input type="number" class="form-control" id="PagoEfectivo"
                                                name="PagoEfectivo">
                                        </div>
                                        <div id="tar5" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="" class="text-uppercase text-muted">Monto de pago con
                                                tarjeta</label>
                                            <input type="number" class="form-control" id="PagoTarjeta" name="PagoTarjeta">
                                        </div>
                                        <div id="tar10" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="banco" class="text-uppercase text-muted">Pago a meses</label>
                                            <select class="form-control" name="mesesPago" id="banco">
                                                <option value="0">Selecciona...</option>
                                                <option value="3">3 meses</option>
                                                <option value="6">6 meses</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{--Sigpesos--}}

                                    <div  id="PagoSigpesos" style="display: none;">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex bd-highlight">
                                                    <div class="p-2 w-100 bd-highlight">
                                                        <label>Cupones de Sigpesos</label>
                                                    </div>
                                                    <div class="p-2 flex-shrink-1 bd-highlight">
                                                        <a href="javascript:void(0);" id="agregarCupon" class="add_button" title="Agregar cupon"><i class="fas fa-plus"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 form-group">
                                                <label for=""> Folio</label>
                                                <input type="number" class="form-control folio" name="folio[]" required="" >
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 form-group">
                                                <label for=""> Monto</label>
                                                <input type="number" class="form-control inputPesos" name="monto[]" onchange="cienporciento()">
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 form-group">
                                                <label for=""> Lista Folio</label>
                                                <select   name="lista[]" class="form-control lista" required>
                                                    <option value="">Seleccionar</option>
                                                    @foreach ($Folios as $Folio1)
                                                    <option value="{{$Folio1->id}}">
                                                        {{$Folio1->descripcion}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="field_wrapper"></div>
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-md-4 form-group">

                                                <label for="" class="text-uppercase text-muted">Total de sigpesos a usar: </label>

                                                <input type="number" class="form-control" name="sigpesos_usar"
                                                    id="sigpesos_usar" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="paciente_id" id="paciente_id" required>
                                    <div class="row">
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Paciente: </label>
                                        <input type="text" class="form-control" id="inputNombrePaciente" required
                                                readonly>
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Fecha: </label>
                                            @php
                                               $date =  new DateTime(); 
                                                $date->modify('-5 hours');
                                               
                                            @endphp
                                            
                                            <input type="text" name="fecha" class="form-control" readonly=""value="{{$date->format('Y-m-d H:i:s')}}" required="">
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="" class="text-uppercase text-muted">Folio: </label>
                                            <input type="number" name="precio" class="form-control" readonly=""
                                                value="{{$folio}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- INPUT SIGPESOS GANADOS --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Sigpesos ganados: </label>

                                            <input type="number" class="form-control" name="sigpesos" id="sigpesos"
                                                value="0" min="0" step="0.01" readonly="">
                                        </div>

                                        {{-- INPUT SALDO A FAVOR --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Saldo a favor: </label>

                                            <input type="number" class="form-control" name="saldo_a_favor" id="saldoAFavor"
                                                value="0" min="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT SIGPESOS A USAR --}}

                                        {{-- INPUT SUBTOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Subtotal: $</label>

                                            <input type="number" required="" class="form-control" name="subtotal"
                                                id="subtotal" value="0" min="1" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT DESCUENTO --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Descuento: $</label>

                                            <input type="number" required="" class="form-control" name="descuento"
                                                id="descuento" value="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT IVA --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Iva: $</label>

                                            <input type="number" required="" class="form-control" name="iva" id="iva"
                                                value="0" min="1" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT TOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Total: $ </label>

                                            <input type="number" required="" class="form-control" name="total"
                                                id="total" value="0" min="1" step="0.01" readonly>
                                        </div>
                                        {{-- INPUT DESCUENTO --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Descuento de cumpleaños: $</label>

                                            <input type="number" required="" class="form-control" name="descuentoCum"
                                                id="descuentoCumple" value="0" step="0.01" readonly="">
                                        </div>
                                    </div>
                                    {{-- Comentario --}}
                                    <div class="row">
                                        
                                        <div  class="col-12 col-sm-12 col-md-12 form-group">
                                            <label for="" class="text-uppercase text-muted">Comentario</label>
                                            <input type="text" class="form-control" id="comentario"
                                                name="comentario">
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div  class="col-12 col-sm-12 col-md-12 form-group">
                                            
                                            <a class="btn btn-success rounded-0" onclick="javascript:redondear();">
                                                <i class="fa fa-check"></i>Redondear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-12">
                            <p>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1"
                                        data-toggle="collapse" href="#collapseExample" role="button"
                                        aria-expanded="false" aria-controls="collapseExample" name="facturar" value="1">
                                    <label class="custom-control-label" for="customCheck1">FACTURAR</label>
                                </div>
                            </p>
                            <div class="collapse" id="collapseExample">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">TIPO PERSONA</label>
                                            <input type="text" class="form-control" id="tipoPersona" name="tipo_persona">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">NOMBRE / RAZÓN
                                                SOCIAL</label>
                                            <input type="text" class="form-control" id="nombreORazonSocial" name="nombre_o_razon_social">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">RÉGIMEN FISCAL
                                                SOCIAL</label>
                                            <input type="text" class="form-control" id="regimeFiscal" name="regimen_fiscal">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">CORREO</label>
                                            <input type="text" class="form-control" id="correo" name="correo">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">RFC</label>
                                            <input type="text" class="form-control" id="rfc" name="rfc">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">HOMOCLAVE</label>
                                            <input type="text" class="form-control" id="homoclave" name="homoclave">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">CALLE</label>
                                            <input type="text" class="form-control" id="calle" name="calle">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">NUM. EXT</label>
                                            <input type="text" class="form-control" id="num_ext" name="num_ext">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">NUM. INT</label>
                                            <input type="text" class="form-control" id="num_int" name="num_int">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">CP</label>
                                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">Ciudad</label>
                                            <input type="text" class="form-control" id="ciudad" name="ciudad">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">Delegación o municipio</label>
                                            <input type="text" class="form-control" id="alcaldia_o_municipio" name="alcaldia_o_municipio">
                                        </div>
                                        <div class="col-12 col-md-3 mt-3">
                                            <label for="" class="text-uppercase text-muted">Uso cfdi</label>
                                            <select name="uso_cfdi" class="form-control" id="uso_cfdi">
                                                <option value="">Seleccionar</option>
                                                <option value="D01 - Honorarios médicos, dentales y gastos hospitalarios">D01 - Honorarios médicos, dentales y gastos hospitalarios</option>
                                                <option value="D02 - Gastos médicos por incapacidad o discapacidad">D02 - Gastos médicos por incapacidad o discapacidad</option>
                                                <option value="D03 - Gastos funerales">D03 - Gastos funerales</option>
                                                <option value="D04 - Donativos">D04 - Donativos</option>
                                                <option value="D05 - Interéses reales efectivamente pagados por créditos hipotecarios (casa habitación)">D05 - Interéses reales efectivamente pagados por créditos hipotecarios (casa habitación)</option>
                                                <option value="D06 - Aportaciones voluntarias al SAR">D06 - Aportaciones voluntarias al SAR</option>
                                                <option value="D08 - Gastos de transportación escolar obligatoria">D08 - Gastos de transportación escolar obligatoria</option>
                                                <option value="D09 - Depositos en cuentas para el ahorro, primas que tengan como base planes de pensión">D09 - Depositos en cuentas para el ahorro, primas que tengan como base planes de pensión</option>
                                                <option value="D10 - Pagos por servicios educativos (colegiaturas)">D10 - Pagos por servicios educativos (colegiaturas)</option>
                                                <option value="G01 - Adquisición de mercancias">G01 - Adquisición de mercancias</option>
                                                <option value="G02 - Devoluciones, descuentos o bonificaciones">G02 - Devoluciones, descuentos o bonificaciones</option>
                                                <option value="G03 - Gastos en general">G03 - Gastos en general</option>
                                                <option value="I01 - Construcciones">I01 - Construcciones</option>
                                                <option value="I02 - Moviliario y equipo de oficina por inversiones">I02 - Moviliario y equipo de oficina por inversiones</option>
                                                <option value="I03 - Equipo de transporte">I03 - Equipo de transporte</option>
                                                <option value="I04 - Equipo de cómputo y accesorios">I04 - Equipo de cómputo y accesorios</option>
                                                <option value="I05 - Dados, troqueles, moldes, matrices y herramental">I05 - Dados, troqueles, moldes, matrices y herramental</option>
                                                <option value="I06 - Comunicaciones telefónicas">I06 - Comunicaciones telefónicas</option>
                                                <option value="I07 - Comunicaciones satelitales">I07 - Comunicaciones satelitales</option>
                                                <option value="I08 - Otra maquinaria y equipo">I08 - Otra maquinaria y equipo</option>
                                                <option value="P01 - Por definir">P01 - Por definir</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </form>
                    {{-- BOTON GUARDAR --}}
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success rounded-0" onclick="javascript:sendFormValidador();">
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
    class FormValidator{

        static getPorcentajes(){
            return $('.inputPesos').map( function(){
                return parseFloat(this.value)
            } ).get();
        }

        static getTotalPorcentaje(){

            let porcentajes = this.getPorcentajes()

            let total = 0;
            for (let i = 0; i < porcentajes.length; i++) {
                total += porcentajes[i] << 0;
            }

            console.log(total)

            return total;

        }

        static faltaPorcentaje(){
            return this.getTotalPorcentaje();
        }

    }

    function cienporciento(){
        $('#sigpesos_usar').val(FormValidator.faltaPorcentaje());
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var desCumple=parseFloat($('#descuentoCumple').val());

        var saldoAFavor=parseFloat($('#saldoAFavor').val());
        //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
        $('#iva').val(getIva);
        var iva=getIva;
        var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-saldoAFavor;
        if (aux>0) {
            $('#total').val(aux.toFixed(2));
        }else{
            $('#total').val(0);
        }
        console.log('TOTAL ACTUALIZADO',$('#total').val());

    }


    $(document).ready(function(){
        var maxField = 100; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = `
        <div class="row">
            <div class="col-12">
                <div class="d-flex bd-highlight">
                    <div class="p-2 w-100 bd-highlight">
                        <label>Cupones de Sigpesos</label>
                    </div>
                    <div class="p-2 flex-shrink-1 bd-highlight">
                        <a href="javascript:void(0);" class="remove_button" title="Add field"><i class="fas fa-minus-circle"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 form-group">
                <label for=""> Folio</label>
                <input type="text" class="form-control folio" name="folio[]" required="" >
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 form-group">
                <label for=""> Monto</label>
                <input type="text" class="form-control inputPesos" name="monto[]" onchange="cienporciento()" >
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 form-group">
                <label for=""> Lista Folio</label>
                <select   name="lista[]" class="form-control lista" required>
                    <option value="">Seleccionar</option>
                    @foreach ($Folios as $Folio)
                    <option value="{{$Folio->id}}">
                        {{$Folio->descripcion}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        `;
  




        // '<div class="input-group offset-md-4 col-md-6"> <select id="uva" class="form-control" name="uva[]"><option value="">Seleccione su uva</option></select><input type="number" step="any" min="0.00" placeholder="Hectareas" class="form-control" name="hectarea[]" value=""/><div class="input-group-append"><span class="input-group-text"><strong>ha</strong></span></div><div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1">$</span></div><input type="number" step="any" min="0.00" placeholder="Costo de la uva" class="form-control" name="costo[]" value=""/><div class="input-group-append"><span class="input-group-text">USD</span></div></div></div>'; //New input field html 
        var x = 1; //Initial field counter is 1
        
        //Once add button is clicked
        $(addButton).click(function(){
            //Check maximum number of input fields

            if(x < maxField){ 
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });
        
        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>

   
<script type="text/javascript">
    function redondear(){
        $('#total').val(parseFloat($('#total').val()).toFixed(0));
        
    }
    function sendFormValidador() {
        console.log("empleado",$('#empleado_id').val());  
        console.log("id del paciente: ",$('#empleado_id').val());
        
    if ($('#empleado_id').val()!="" && $('#paciente_id').val()) {
        if (parseFloat($('#total').val())==(parseFloat($('#PagoTarjeta').val())+parseFloat($('#PagoEfectivo').val()))) {
                document.getElementById("form-cliente").submit();        
            } 
            else {
                 alert("Valida los campos de forma de pago");
                 return false;
                 }
               }
                 else{
                 alert("Valida el campo de empleado o paciente");
                 return false;
             }
                   
    } 

    function on(){
         $('#descuento').val(0);
         $('#descuento').val(parseFloat(parseFloat($('#subtotal').val())*.05).toFixed(2));
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
        $('#iva').val(getIva);
        var iva=getIva;
        var saldoAFavor=parseFloat($('#saldoAFavor').val());
        //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        
        // console.log(des);
        console.log('SUBTOTAL', subtotal);
        console.log('iva', iva);
        console.log('des', des);
        console.log('sigpesos', sigpesos);  
        console.log('TOTAL ACTUALIZADO',parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple));
        var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
        if (aux>0) {
            $('#total').val(aux.toFixed(2));
        }else{
            $('#total').val(0);
        }
        
        
        $('#PromocionDescuento').hide();

    }

    function off(){
        $('#descuento').val(0);
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        //var getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
        $('#iva').val(getIva);
        var saldoAFavor=parseFloat($('#saldoAFavor').val());
        var iva=getIva;
        // console.log(des);
        console.log('SUBTOTAL', subtotal);
        console.log('iva', iva);
        console.log('des', des);
        console.log('sigpesos', sigpesos);  
        console.log('TOTAL ACTUALIZADO',parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple));
        var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
        if (aux.toFixed(2)!=$('#total').val()) {
            if (aux>0) {
                $('#total').val(aux.toFixed(2));
            }else{
                $('#total').val(0);
            }
        }
        
        $('#PromocionDescuento').show();
    }

    var checkbox = document.getElementById('INAPAM');

    checkbox.addEventListener("change", comprueba, false);

    function comprueba(){
        var pacienteId=$('#paciente_id').val();
        if(checkbox.checked){
            $.ajax({
                url:`{{ url('/api/pacientes/${pacienteId}/inapam') }}`,
                type: 'GET',
                success: function(inapam){
                    if (inapam=="1") {
                        var opcion = confirm("Se cargar la INAPAM despues ");
                        if (opcion == true) {
                            on();
                        } else {
                           $("#INAPAM").prop("checked", false); 
                           $('#PromocionDescuento').show();
                        }
                    }else{
                        on();
                    }
                }
            });
            
            
        }else{
            off();
        }
    }

</script>       
<script>
    
    function agregarProducto(p){
        let producto = JSON.parse($(p).val());
        // alert(producto);
        if ( $(`#producto_agregado${producto.id}`).length > 0 ) {
            cambiarTotal2($(`#producto_agregado_cantidad${producto.id}`), $(`#producto_agregado${producto.id}`));
        }else{
            if (producto.stock>0) {
                $('#tbody_productos')
                .append(`
                <tr id="producto_agregado${producto.id}">
                    <td>

                        <input class="form-control cantidad" id="producto_agregado_cantidad${producto.id}" min="1" onchange="cambiarTotal(this, '#producto_agregado${producto.id}')" type="number" name="cantidad[]" value="1" stock="${producto.stock}" iva=${producto.precio_publico_iva}>
                        <input class="form-control" type="hidden" name="producto_id[]" value="${producto.id}" iva=${producto.precio_publico_iva}>

                    </td>
                    <td>
                        ${producto.descripcion}
                    </td>
                    <td class="precio_individual">
                        ${producto.precio_publico}
                    </td>
                    <td class="precio_individual_iva">${producto.precio_publico_iva}</td>
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
                $('#BuscarProducto').val("");
            }else{
                alert('Producto sin stock');
            }
        } 
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
        $('#PromocionDescuento').show();
        $("#INAPAM").prop("checked", false);
        $('#sigpesos').val(0);
        $('#subtotal').val(total.toFixed(2));
        let getIva = ($('#subtotal').val()*0.16);
        $('#iva').val(getIva.toFixed(2));
        //console.log(getIva.toFixed(2));
        if ($('#sigpesos_usar').val()==null) {
             $.ajax({
                url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
                type:'GET',
                dataType:'json',
                success: function(res){
                   if (!isNaN(res.sigpesos)&&res.sigpesos!="") {
                        var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                        console.log('sigpesos peticione4444',res.sigpesos);
                    }else{             
                        res.sigpesos=0;       
                        var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                        console.log('sigpesos peticion5555',res.sigpesos);
                    }   
                    
                    $('#descuentoCumple').val(parseInt(res.cumple));
                    if (res.cumple>0) {
                        $('#cumpleDes').val(1);
                    }
                }
            });
             console.log('sigpesos3rff', sigpesos);
        }
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        var saldoAFavor=parseFloat($('#saldoAFavor').val());

        getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
        $('#iva').val(getIva);
        var iva=getIva;
        // console.log(des);
        console.log('getIva', getIva);
        console.log('SUBTOTAL', subtotal);
        console.log('iva999', iva);
        console.log('des', des);
        console.log('sigpesos', sigpesos);  
        console.log('desCumple', desCumple);  
        console.log('TOTAL ACTUALIZADO',parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor));
        var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
        if (aux>0) {
            $('#total').val(aux.toFixed(2));
        }else{
            $('#total').val(0);
        }
        // $('#total').val('ola');
    }

    function cambiarTotal2(a, p){

        let cant = parseFloat(a.val());
        cant=cant+1;
        if (a.attr("stock")>cant) {
            let cantiva = parseFloat(a.attr("iva"));
            let ind = parseFloat($(p).find('.precio_individual').first().text());
            let total = cant*ind;
            let totaliva = cantiva*cant;
            console.log('----------',ind);
            $(p).find('.precio_total').text(total);
            $(p).find('.precio_individual_iva').text(parseFloat(totaliva).toFixed(2));
            a.val(parseFloat(cant));
            cambiarTotalVenta();
        }else{
        alert('Producto sin stock necesario');
    }
        
    }
    function cambiarTotal(a, p){

        let cant = parseFloat(a.value);
        if (a.getAttribute("stock")>cant) {
            let cantiva = parseFloat(a.getAttribute("iva"));
            let ind = parseFloat($(p).find('.precio_individual').first().text());
            let total = cant*ind;
            let totaliva = cantiva*cant;
            console.log('----------',ind);
            $(p).find('.precio_total').text(total);
            $(p).find('.precio_individual_iva').text(parseFloat(totaliva).toFixed(2));
            cambiarTotalVenta();
        }else{
        alert('Producto sin stock necesario');
    }
        
    }


    $(document).ready(function () {
        $('#sigpesos_usar').change(function(){
            var subtotal=parseFloat($('#subtotal').val());
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var desCumple=parseFloat($('#descuentoCumple').val());
            var saldoAFavor=parseFloat($('#saldoAFavor').val());
             saldoAFavor = sigpesos;
            //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
            //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
            var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
            $('#iva').val(getIva);
            var iva=getIva;
            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
            if (aux>0) {
                $('#total').val(aux.toFixed(2));
            }else{
                $('#total').val(0);
            }
            
            console.log('TOTAL ACTUALIZADO',$('#total').val());
         });

        $('#tipoPago').change(function(){  
            console.log('Entra');
            if ($('#tipoPago').val()==2){
                $('#PagoEfectivo').val(0);
                $('#PagoTarjeta').val(0);
                
                $('#tar1').show();
                $('#tar2').show();
                $('#tar5').show();
                $('#tar10').show();
                $('#tar4').hide();
                $('#PagoSigpesos').hide();
                $('#digitos_targeta').required;
                
                
                //$('#sigpesos_usar').val(0);
                var subtotal=parseFloat($('#subtotal').val());
                var des=parseFloat($('#descuento').val());
                var sigpesos=parseInt($('#sigpesos_usar').val());
                var desCumple=parseFloat($('#descuentoCumple').val());
                var saldoAFavor=parseFloat($('#saldoAFavor').val());
                 saldoAFavor = sigpesos;
                //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                $('#iva').val(getIva);
                var iva=getIva;
                var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
                if (aux>0) {
                    $('#total').val(aux.toFixed(2));
                }else{
                    $('#total').val(0);
                }
                console.log('TOTAL ACTUALIZADO DESDE TARJETA',$('#total').val());


                $('#PagoTarjeta').val($('#total').val());

            }
            else if ($('#tipoPago').val()==3) {
                $('#PagoEfectivo').val(0);
                $('#PagoTarjeta').val(0);

                $('#tar1').show();
                $('#tar2').show();
                $('#tar4').show();
                $('#tar5').show();
                $('#tar10').show();
                $('#PagoSigpesos').show();
                $('#digitos_targeta').required;
                var sigpesos=parseInt($('#sigpesos_usar').val());
                var saldoAFavor=parseFloat($('#saldoAFavor').val());
                saldoAFavor = sigpesos;
                console.log('TOTAL ACTUALIZADO DESDE COMBINADO',$('#total').val());
                console.log('Sipesos:',sigpesos);
               
            }
            else if ($('#tipoPago').val()==1) {
                $('#PagoEfectivo').val(0);
                $('#PagoTarjeta').val(0);

               $('#banco').val(null);
                $('#digitos_targeta').val(null);
                $('#tar1').hide();
                $('#tar2').hide();
                $('#tar4').show();
                $('#tar5').hide();
                $('#tar10').hide();
                $('#PagoSigpesos').hide();


                //$('#sigpesos_usar').val(0);
                var subtotal=parseFloat($('#subtotal').val());
                var des=parseFloat($('#descuento').val());
                var sigpesos=parseInt($('#sigpesos_usar').val());
                var desCumple=parseFloat($('#descuentoCumple').val());
                //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));                   
                 var saldoAFavor=parseFloat($('#saldoAFavor').val());
                 var saldoAFavor = sigpesos;
                var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                $('#iva').val(getIva);
                var iva=getIva;
                var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
                if (aux>0) {
                    $('#total').val(aux.toFixed(2));
                }else{
                    $('#total').val(0);
                }
                console.log('TOTAL ACTUALIZADO DESDE EFECTIVO',$('#total').val());
                console.log('Saldo a favor:',saldoAFavor);
                

                $('#PagoEfectivo').val($('#total').val());

            }
            else if($('#tipoPago').val()==4){
                $('#PagoEfectivo').val(0);
                $('#PagoTarjeta').val(0);

                $('#PagoSigpesos').show();
                $('#banco').val(null);
                $('#digitos_targeta').val(null);
                $('#tar1').hide();
                $('#tar2').hide();
                $('#tar4').hide();
                $('#tar5').hide();
                $('#tar10').hide();

                    //Agregando lineas para actualizar el sigpesos                   
                var subtotal=parseFloat($('#subtotal').val());
                var des=parseFloat($('#descuento').val());
                var sigpesos=parseInt($('#sigpesos_usar').val());
                var desCumple=parseFloat($('#descuentoCumple').val());
                var saldoAFavor=parseFloat($('#saldoAFavor').val());
                var saldoAFavor = sigpesos;
                //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                console.log(sigpesos);
                $('#iva').val(getIva);
                var iva=getIva;
                var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
                if (aux>0) {
                    $('#total').val(aux.toFixed(2));
                }else{
                    $('#total').val(0);
                }
                console.log('TOTAL ACTUALIZADO EN SIGPESOS',$('#total').val());
               console.log('Saldo a favor:',saldoAFavor);
              


            }

            else

            {
                $('#PagoEfectivo').val(0);
                $('#PagoTarjeta').val(0);

                $('#banco').val(null);
                $('#digitos_targeta').val(null);
                $('#tar1').hide();
                $('#tar2').hide();
                $('#tar4').hide();
                $('#tar5').hide();
                $('#tar10').hide();
                $('#PagoSigpesos').hide();

                $('#sigpesos_usar').val(0);
                var subtotal=parseFloat($('#subtotal').val());
                var des=parseFloat($('#descuento').val());
                var sigpesos=parseInt($('#sigpesos_usar').val());
                var desCumple=parseFloat($('#descuentoCumple').val());
                var saldoAFavor=parseFloat($('#saldoAFavor').val());
                var saldoAFavor = sigpesos;
                //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                console.log(sigpesos);
                $('#iva').val(getIva);
                var iva=getIva;
                var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
                if (aux>0) {
                    $('#total').val(aux.toFixed(2));
                }else{
                    $('#total').val(0);
                }
                console.log('TOTAL ACTUALIZADO EN SIGPESOS',$('#total').val());
                console.log('Saldo a favor:',saldoAFavor);

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
            $("#INAPAM").prop("checked", false);
            $('#sigpesos').val(0);
            var subtotal=parseFloat($('#subtotal').val());
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var desCumple=parseFloat($('#descuentoCumple').val());
            var saldoAFavor=parseFloat($('#saldoAFavor').val());
            var saldoAFavor = sigpesos;
            //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
            //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
            var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
            $('#iva').val(getIva);
            var iva=getIva;
            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
            if (aux>0) {
                $('#total').val(aux.toFixed(2));
            }else{
                $('#total').val(0);
            }
            $.ajax({
                url:"{{ url('/get_promos') }}/"+id,
                type:'GET',
                dataType:'html',
                success: function(res){
                    $('#promocion_id').html(res);

                    $('#sigpesos_usar').prop("disabled", false);
                    $('#agregarCupon').show();
                    $('.folio').prop("disabled", false);
                    $('.inputPesos').prop("disabled", false);
                    $('.lista').prop("disabled", false);
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
            var des=parseFloat($('#descuento').val());
            var sigpesos=parseInt($('#sigpesos_usar').val());
            var desCumple=parseFloat($('#descuentoCumple').val());
            var saldoAFavor=parseFloat($('#saldoAFavor').val());
            var saldoAFavor = sigpesos;
            //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
            //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
            var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
            $('#iva').val(getIva);
            var iva=getIva;
            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
            if (aux>0) {
                $('#total').val(aux.toFixed(2));
            }else{
                $('#total').val(0);
            }
            
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
                    //alert(res.sigpesos);                  
                    if(res.status){
                        if (res.status==1) {                       
                            $('#descuento').val(res.total);
                            $('#sigpesos').val(res.sigpesos);
                            des=parseFloat($('#descuento').val());
                            //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                            //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                            var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                            var saldoAFavor=parseFloat($('#saldoAFavor').val());
                            var saldoAFavor = sigpesos;
                            $('#iva').val(getIva);
                            var iva=getIva;
                            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
                            $('#total').val(aux.toFixed(2));
                            if($('#total').val()<0)
                            {
                                $('#total').val(0);
                            }
                        }else{
                            $('#descuento').val(res.total);
                            $('#sigpesos').val(res.sigpesos);
                            des=parseFloat($('#descuento').val());
                            //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                            //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                            var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                            $('#iva').val(getIva);
                            var iva=getIva;
                            var saldoAFavor=parseFloat($('#saldoAFavor').val());
                            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
                            $('#total').val(aux.toFixed(2));
                            if($('#total').val()<0)
                            {
                                $('#total').val(0);
                            }
                        }

                        if (res.aceptsp==0) {
                            $('#sigpesos_usar').val(0);
                            $('#sigpesos_usar').prop("disabled", true);

                            $('#agregarCupon').hide();
                            $('.folio').prop("disabled", true);
                            $('.inputPesos').prop("disabled", true);
                            $('.lista').prop("disabled", true);
                              
                            
                        }else if (res.aceptsp==1) {
                            $('#sigpesos_usar').prop("disabled", false);

                            $('#agregarCupon').show();
                            $('.folio').prop("disabled", false);
                            $('.inputPesos').prop("disabled", false);
                            $('.lista').prop("disabled", false);
                        }
                        //$('#total').val()
                    }
                    else
                    {
                        swal("No aplica el descuento");
                        $('#promocion_id option:eq(0)').prop('selected',true);
                        $('#sigpesos_usar').prop("disabled", false);
                        $('#agregarCupon').show();
                        $('.folio').prop("disabled", false);
                        $('.inputPesos').prop("disabled", false);
                        $('.lista').prop("disabled", false);
                    }
                },
                error: function(e){
                    alert('No se aplicó la promoción');
                    console.log(e);
                    $('#sigpesos_usar').prop("disabled", false);
                    $('#agregarCupon').show();
                    $('.folio').prop("disabled", false);
                    $('.inputPesos').prop("disabled", false);
                    $('.lista').prop("disabled", false);
                }

            });
        });
       
        $('#paciente_id').change( async function(){
            var pacienteId=$(this).val();
            
            
        });
        $("#BuscarPaciente").on('keyup', function (e) {
          var keycode = e.keyCode || e.which;
            if (keycode == 13) {
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

            }
        });
        /*$('#BuscarPaciente').change( function() {
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
        });*/
        $("#BuscarProducto").on('keyup', function (e) {
          var keycode = e.keyCode || e.which;
            if (keycode == 13) {
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
            }
        });
        /*$('#BuscarProducto').change( function() {
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
        });*/
    });

   @if(!isset($paciente))
    $(document).on('click', '.botonSeleccionCliente', async function(){
        
                
        const pacienteId = $(this).attr('pacienteid');

        $.ajax({
            url:`{{ url('/api/pacientes/${pacienteId}/datos_fiscales') }}`,
            type: 'GET',
            success: function(datos_fiscales){
                console.log({
                    pacienteId,
                    datos_fiscales
                });

                if(datos_fiscales.datosFiscales != null){
                $('#tipoPersona').val(datos_fiscales.datosFiscales.tipo_persona);
                $('#nombreORazonSocial').val(datos_fiscales.datosFiscales.nombre_o_razon_social);
                $('#regimeFiscal').val(datos_fiscales.datosFiscales.regimen_fiscal);
                $('#correo').val(datos_fiscales.datosFiscales.correo);
                $('#rfc').val(datos_fiscales.datosFiscales.rfc);
                $('#homoclave').val(datos_fiscales.datosFiscales.homoclave);
                $('#calle').val(datos_fiscales.datosFiscales.calle);
                $('#num_ext').val(datos_fiscales.datosFiscales.num_ext);
                $('#num_int').val(datos_fiscales.datosFiscales.num_int);
                $('#codigo_postal').val(datos_fiscales.datosFiscales.codigo_postal);
                $('#ciudad').val(datos_fiscales.datosFiscales.ciudad);
                $('#homoclave').val(datos_fiscales.datosFiscales.homoclave);
                $('#alcaldia_o_municipio').val(datos_fiscales.datosFiscales.alcaldia_o_municipio);
                $('#uso_cfdi').val(datos_fiscales.datosFiscales.uso_cfdi);
                }

                $('#saldoAFavor').val(datos_fiscales.saldo_a_favor);
                console.table(datos_fiscales)
            }
        });

        $.ajax({
            url:`{{ url('/api/pacientes/${pacienteId}/inapam') }}`,
            type: 'GET',
            success: function(inapam){
                if (inapam=="1") {
                    $('#ErrorInapam').show();
                }else{
                    $('#ErrorInapam').hide();
                }                
            }
        });
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
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
        var saldoAFavor=parseFloat($('#saldoAFavor').val());
        $('#iva').val(getIva);
        var iva=getIva;
        await $.ajax({
            url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
            type:'GET',
            dataType:'json',
            success: function(res){
                 console.log('sigpesos peticion198711',res);
                 console.log('sigpesos peticion198712',res.cumple);

                if (!isNaN(res.sigpesos)&&res.sigpesos!="") {
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                    console.log('sigpesos peticion00',res);
                }else{             
                    res.sigpesos=0;       
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res.sigpesos));
                    console.log('sigpesos peticion111',res.sigpesos);
                }
                console.log('sigpesos peticion198712',res.cumple);
                $('#descuentoCumple').val(parseInt(res.cumple));
                if (res.cumple>0) {
                        $('#cumpleDes').val(1);
                    }
                var desCumple=parseFloat($('#descuentoCumple').val());
                //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                $('#iva').val(getIva);
                var iva=getIva;



            }

        });
        
        if((subtotal+iva-des-desCumple-saldoAFavor)<$('#sigpesos_usar').val())
        {
            $('#total').val(0);
        }
        else
        {
            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(desCumple)-$('#sigpesos_usar').val()-parseFloat($('#saldoAFavor').val());
            if (aux>0) {
                $('#total').val(aux.toFixed(2));
            }else{
                $('#total').val(0);
            }
            
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
            <?php
            if ($paciente->expediente()->first()!=null) {
                if ($paciente->expediente()->first()->inapam==null) {
                    echo "$('#ErrorInapam').show();";
                }
            } else {
                echo "$('#ErrorInapam').show();";
            }
            //dd($paciente->expediente()->first()->inapam);
            ?>
        
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
        //var iva=parseFloat($('#iva').val());
        var des=parseFloat($('#descuento').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        var saldoAFavor=parseFloat($('#saldoAFavor').val());
        var saldoAFavor = sigpesos;
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
        $('#iva').val(getIva);
        var iva=getIva; 
        $.ajax({
            url:"{{ url('/obtener_sigpesos') }}/"+pacienteId,
            type:'GET',
            dataType:'json',
            success: function(res34){   
                if (!isNaN(res34.sigpesos)&&res34.sigpesos!="") {
                    var sigpesos=$('#sigpesos_usar').val(parseInt(res34.sigpesos));
                    console.log('sigpesos peticion00',res34.sigpesos);
                }else{             
                    res34=0;       
                    var sigpesos=$('#sigpesos_usar').val(0);
                    console.log('sigpesos peticion1199',0);
                }
                $('#descuentoCumple').val(parseInt(res34.cumple));
                if (res34.cumple>0) {
                    $('#cumpleDes').val(1);
                }
                var desCumple=parseFloat($('#descuentoCumple').val());
                //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                $('#iva').val(getIva);
                var iva=getIva;

            }

        });
        if((subtotal+iva-des-desCumple-saldoAFavor)<$('#sigpesos_usar').val())
        {
            $('#total').val(0);
        }
        else
        {
            
            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(desCumple)-$('#sigpesos_usar').val()-parseFloat($('#saldoAFavor').val());
            if (aux>0) {
                $('#total').val(aux.toFixed(2));
            }else{
                $('#total').val(0);
            }
            console.log('total',$('#sigpesos_usar').val())
        }
    });

   

</script>
@else
@endif
@endsection

