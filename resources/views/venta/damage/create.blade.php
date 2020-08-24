@extends('principal')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@section('content')


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
                <form role="form" id="form-cliente" method="POST" action="{{route('ventaDamage.create')}}" name="form">
                    {{ csrf_field() }}
                    <input type="hidden" name="oficina_id" value="{{session('oficina')}}">
                    <input type="hidden" name="cumpleDes" id="cumpleDes" value="0">
                    <input type="hidden" name="productoDevuelto" id="productoDevuelto" value="{{$productoDebuelto->id}}">
                    <input type="hidden" name="TipoDamage" id="TipoDamage" value="{{$TipoDamage}}">
                    <input type="hidden" name="DesDamage" id="DesDamage" value="{{$DesDamage}}">
                    <input type="hidden" name="folio_nuevo" id="folio_nuevo" value="{{$folio+4}}">
                    <input type="hidden" name="VentaAnterior" id="VentaAnterior" value="{{$VentaA}}">

                    <input type="hidden" class="form-control" name="montonegativo"
                                                id="montonegativo" value="0" min="1" step="0.01" value="{{$saldo-$producto->precio_publico_iva}}
                                                " >

                    <div class="row">
                        <div class="col-4 form-group">
                            <label class="control-label">Fitter:</label>
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
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_productos">
                                                    <tr id="producto_agregado{{$producto->id}}">
                                                        <td>

                                                            <input class="form-control cantidad" min="1"  type="number" name="cantidad[]" value="1" stock="{{$producto->stock}}" iva="{{$producto->precio_publico_iva}}" readonly>

                                                            <input class="form-control" type="hidden" name="producto_id[]" value="{{$producto->id}}" iva={{$producto->precio_publico_iva}}>

                                                        </td>
                                                        <td>
                                                            {{$producto->descripcion}}
                                                        </td>
                                                        <td class="precio_individual">
                                                            {{$producto->precio_publico}}
                                                        </td>
                                                        <td class="precio_individual_iva">{{$producto->precio_publico_iva}}</td>
                                                        <td class="precio_total">
                                                            {{$producto->precio_publico}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>

                                    
                                    
                                    {{-- Pagos Y tarjeta --}}
                                    <div class="row">
                                        {{-- INPUT Tipo de pago --}}
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <label for="tipoPago" class="text-uppercase text-muted">Tipo de pago</label>
                                            <select class="form-control" name="tipoPago" id="tipoPago">
                                                <option value="0">Selecciona...</option>
                                                <option value="1">Efectivo</option>
                                                <option value="2">Tajeta</option>
                                                <option value="3">Combinado</option>
                                                <option value="4">Sigpesos</option>
                                            </select>
                                        </div>
                                        {{-- INPUT tarjeta --}}

                                        <div id="tar1" class="col-12 col-sm-6 col-md-4 form-group"
                                            style="display: none;">
                                            <label for="banco" class="text-uppercase text-muted">Banco</label>
                                            <select class="form-control" name="banco" id="banco">
                                                <option value="">Selecciona...</option>
                                                <option value="SANTANDER">Banco</option>
                                                <option value="AMEX">Amex</option>
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
                                            <input type="date" name="fecha" class="form-control" readonly=""
                                                value="{{date('Y-m-d')}}" required="">
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
                                                value="{{$saldo}}" min="0" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT SIGPESOS A USAR --}}

                                        {{-- INPUT SUBTOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Subtotal: $</label>

                                            <input type="number" required="" class="form-control" name="subtotal"
                                                id="subtotal" value="{{$producto->precio_publico}}" min="1" step="0.01" readonly="">
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
                                                value="{{$producto->precio_publico_iva-$producto->precio_publico}}" min="1" step="0.01" readonly="">
                                        </div>
                                        {{-- INPUT TOTAL --}}
                                        <div class="col-12 col-sm-6 col-md-4 mt-2">

                                            <label for="" class="text-uppercase text-muted">Total: $ </label>

                                            <input type="number" required="" class="form-control" name="total"
                                                id="total" value="0" min="1" step="0.01" value="{{$producto->precio_publico_iva-$saldo}}" readonly>
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
            $('#montonegativo').val(-aux.toFixed(2));
            
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
        if ($('#empleado_id').val()!="") {
            if (parseFloat($('#total').val())==(parseFloat($('#PagoTarjeta').val())+parseFloat($('#PagoEfectivo').val()))) {
                document.getElementById("form-cliente").submit();
            } else {
                alert("Valida los campos de forma de pago");
                return false;
            }
        }else{
            alert("Valida el campo de empleado");
            return false;
        }
      
    }
</script>
<script>
    $(document).ready(function () {
        $('#sigpesos_usar').change(function(){
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
            var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
           
            if (aux>0) {
                $('#total').val(aux.toFixed(2));
            }else{
                $('#total').val(0);
                $('#montonegativo').val(-aux.toFixed(2));
                
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
                
                
                $('#sigpesos_usar').val(0);
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
                var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
                if (aux>0) {
                    $('#total').val(aux.toFixed(2));
                }else{
                    $('#total').val(0);
                    $('#montonegativo').val(-aux.toFixed(2));
                    
                }
                console.log('TOTAL ACTUALIZADO',$('#total').val());


                $('#PagoTarjeta').val($('#total').val());

            }else if ($('#tipoPago').val()==3) {
                $('#PagoEfectivo').val(0);
                $('#PagoTarjeta').val(0);

                $('#tar1').show();
                $('#tar2').show();
                $('#tar4').show();
                $('#tar5').show();
                $('#tar10').show();
                $('#PagoSigpesos').show();
                $('#digitos_targeta').required;
            }else if ($('#tipoPago').val()==1) {
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


                $('#sigpesos_usar').val(0);
                var subtotal=parseFloat($('#subtotal').val());
                var des=parseFloat($('#descuento').val());
                var sigpesos=parseInt($('#sigpesos_usar').val());
                var desCumple=parseFloat($('#descuentoCumple').val());
                //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
                //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
                 var saldoAFavor=parseFloat($('#saldoAFavor').val());
                var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
                $('#iva').val(getIva);
                var iva=getIva;
                var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
               
                if (aux>0) {
                    $('#total').val(aux.toFixed(2));
                }else{
                    $('#total').val(0);
                    $('#montonegativo').val(-aux.toFixed(2));
                    
                }
                console.log('TOTAL ACTUALIZADO',$('#total').val());


                $('#PagoEfectivo').val($('#total').val());

            }else if($('#tipoPago').val()==4){
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
            }else{
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
                    $('#montonegativo').val(-aux.toFixed(2));
                }                
                console.log('TOTAL ACTUALIZADO',$('#total').val());

            }

        });
        
    });
</script>




<script type="text/javascript">
    $(document).ready(function(){
        
        var pacienteId = {{$paciente->id}};

        var nombrePaciente = "{{ $paciente->nombre }}";
        var apellidosPaciente = "{{ $paciente->paterno.' '.$paciente->materno }}";
        var saldoAFavor=parseFloat("{{$paciente->saldo_a_favor+$saldo}}");

        console.log('datosPAciente: ',nombrePaciente,apellidosPaciente);
        
        $('#inputNombrePaciente').val( nombrePaciente + " " + apellidosPaciente );
        $('#saldoAFavor').val("{{$paciente->saldo_a_favor+$saldo}}");
        $('#paciente_id').val(pacienteId);
        console.log( 'Cliente seleccionado: ', pacienteId );
        $('#promocion_id option:eq(0)').prop('selected',true);
        $('#descuento').val(0);
        $('#sigpesos').val(0);
       




        var subtotal=parseFloat($('#subtotal').val());
        var des=parseFloat($('#descuento').val());
        var sigpesos=parseInt($('#sigpesos_usar').val());
        var desCumple=parseFloat($('#descuentoCumple').val());
        var saldoAFavor=parseFloat($('#saldoAFavor').val());
        //let getIva = (($('#subtotal').val()-des-desCumple)*0.16);
        //var iva=parseFloat($('#iva').val(getIva.toFixed(2)));
        var getIva = (($('#subtotal').val()-des-desCumple)*0.16).toFixed(2);
        
        var iva=getIva;
        var aux=parseFloat(subtotal)+parseFloat(iva)-parseFloat(des)-parseFloat(sigpesos)-parseFloat(desCumple)-parseFloat(saldoAFavor);
        if (aux>0) {
            $('#total').val(aux.toFixed(2));
        }else{
            $('#total').val(0);
            $('#montonegativo').val(-aux.toFixed(2));
            
        }
        

        $.ajax({
            url:`{{ url('/api/pacientes/${pacienteId}/datos_fiscales') }}`,
            type: 'GET',
            success: function(datos_fiscales){
                
                if(datos_fiscales.datosFiscales != null){
                $('#tipoPersona').val(datos_fiscales.datosFiscales.tipo_persona);
                $('#nombreORazonSocial').val(datos_fiscales.datosFiscales.nombre_o_razon_social);
                $('#regimeFiscal').val(datos_fiscales.datosFiscales.regimen_fiscal);
                $('#correo').val(datos_fiscales.datosFiscales.correo);
                $('#rfc').val(datos_fiscales.datosFiscales.rfc);
                $('#calle').val(datos_fiscales.datosFiscales.calle);
                $('#num_ext').val(datos_fiscales.datosFiscales.num_ext);
                $('#num_int').val(datos_fiscales.datosFiscales.num_int);
                $('#codigo_postal').val(datos_fiscales.datosFiscales.codigo_postal);
                $('#ciudad').val(datos_fiscales.datosFiscales.ciudad);
                $('#alcaldia_o_municipio').val(datos_fiscales.datosFiscales.alcaldia_o_municipio);
                $('#uso_cfdi').val(datos_fiscales.datosFiscales.uso_cfdi);
                }

            }
        });
        
    });

   

</script>
@endsection

