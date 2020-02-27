@extends('layouts.infoempleado')
@section('infoempleado')

<div>
		<ul class="nav nav-pills nav-justified">
			<li role="presentation" class="nav-item"><a href="{{ route('empleados.show',['empleado'=>$empleado]) }}"  class="nav-link">Generales:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.datoslaborales.index',['empleado'=>$empleado]) }}" class="nav-link">Laborales:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.certificaciones.index',['empleado'=>$empleado]) }}" class="nav-link">Estudios:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.emergencias.index',['empleado'=>$empleado]) }}" class="nav-link">Emergencias:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.vacaciones.index',['empleado'=>$empleado]) }}" class="nav-link">Vacaciones:</a></li>
			
			<li role="presentation" class="nav-item"><a href="{{ route('empleados.faltasDH.index',['empleado'=>$empleado]) }}" class="nav-link ">Faltas:</a></li>
			
			<li role="presentation" class="nav-item"><a href="{{ route('empleados.permisos.index',['empleado'=>$empleado]) }}" class="nav-link active">Permisos:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.faltas.index',['empleado'=>$empleado]) }}" class="nav-link">Administrativo:</a></li>
		</ul>
	</div>
	<div class="card">
		<div class="card-header">
			<h4>Permisos:</h4>
		</div>
		<div class="card-body">
			<form role="form" method="POST" action="">
				@csrf
				<div class="row form-group">
					<div class="col-3 offset-2">
						<label for="tipopermiso" class="control-label">Tipo de permiso</label>
						<select id="tipopermiso" name="tipopermiso" class="form-control" required="">
							<option value="">Seleccione el tipo de permiso a otorgar</option>
							<option value="dia">Por día</option>
							<option value="hora">Por hora</option>
						</select>
					</div>
					<div class="col-3">
						<label for="permiso" class="control-label">Permiso</label>
						<select id="permiso" name="permiso" class="form-control" required>
							<option value="">Permiso a otorgar</option>
							<option value="con_sueldo">Con goce de sueldo</option>
							<option value="sin_sueldo">Sin goce de sueldo</option>

							<option value="tiempo">Tiempo por tiempo</option>
						</select>
					</div>
					<div class="col-3">
						<label for="fecha" class="control-label">Fecha del permiso</label>
						<input type="date" name="fecha" class="form-control" id="fecha">
					</div>
				</div>
				<div class="row form-group d-none" id="dia">
					<div class="col-3 offset-2">
						<label for="fechainicio" class="control-label">Fecha de inicio</label>
						<input type="date" name="fechainicio" class="form-control" id="fechainicio">
					</div>
					<div class="col-3">
						<label for="fechafin" class="control-label">Fecha de termino</label>
						<input type="date" name="fechafin" class="form-control" id="fechafin">
					</div>
					<div class="col-3">
						<label for="diastotales" class="control-label">Días solicitados</label>
						<input type="number" step="1" min="0" name="diastotales" class="form-control" id="diastotales">
					</div>
				</div>
				<div class="row form-group d-none" id="hora">
					<div class="col-3 offset-2">
						<label for="horainicio" class="control-label">Hora de inicio</label>
						<input type="time" name="horainicio" class="form-control" id="horainicio">
					</div>
					<div class="col-3">
						<label for="horafin" class="control-label">Hora de termino</label>
						<input type="time" name="horafin" class="form-control" id="horafin">
					</div>
					<div class="col-3">
						<label for="horastotales" class="control-label">Horas solicitadas</label>
						<input type="number" step="0.5" min="0" name="horastotales" class="form-control" id="horastotales">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-5 text-center offset-4">
						<label for="motivo" class="control-label">Especificar motivo:</label>
						<textarea name="motivo" id="motivo" class="form-control" maxlength="500" required></textarea>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-12 text-center">
						<button type="submit" class="btn btn-success">
						 	<strong>Guardar</strong>
						</button>
					</div>
				</div>
			</form>
			<div class="container">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr class="table-info">
							<th scope="col">Tipo de permiso</th>
							<th scope="col">Permiso otorgado</th>
							<th scope="col">Fecha</th>
							<th scope="col">Inicio</th>
							<th scope="col">Fin</th>
							<th scope="col">Total</th>
							<th scope="col">Motivo</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($permisos as $permiso)
							{{-- expr --}}
							<tr>
								<td>{{$permiso->tipo}}</td>
								<td>{{$permiso->perm}}</td>
								<td>{{$permiso->fecha}}</td>
								<td>{{$permiso->inicio}}</td>
								<td>{{$permiso->final}}</td>
								<td>{{$permiso->total}}</td>
								<td>{{$permiso->motivo}}</td>
							</tr>
						@empty
							<div class="alert alert-danger" role="alert">
								El empleado {{$empleado->nombre." ".$empleado->appaterno." ".$empleado->apmaterno}} no tiene registros de permisos
							</div>
						@endforelse
					</tbody>
				</table>
				{{-- Datos generales --}}
				<div class="row">
					{{-- Total de permisos por día --}}
					<div class="col-12 col-md-3">
						<div class="form-group">
							<label for="totalPermisosDia">TOTAL DE PERMISOS POR DÍA</label>
							<input type="text" readonly value="{{$totalPermisosPorDia}}" class="form-control" id="totalPermisosDia">
						</div>
					</div>
					{{-- Total de días permitidos --}}
					<div class="col-12 col-md-3">
						<div class="form-group">
							<label for="totalDiasPermitidos">TOTAL DE DÍAS SOLICITADOS</label>
							<input type="text" readonly value="{{$totalDiasPermitidos}}" class="form-control" id="totalDiasPermitidos">
						</div>
					</div>
					{{-- Total de permisos por hora --}}
					<div class="col-12 col-md-3">
						<div class="form-group">
							<label for="totalPermisosHora">TOTAL DE PERMISOS POR HORA</label>
							<input type="text" readonly value="{{$totalPermisosPorHora}}" class="form-control" id="totalPermisosHora">
						</div>
					</div>
					{{-- Total de días permitidos --}}
					<div class="col-12 col-md-3">
						<div class="form-group">
							<label for="totalHorasPermitidas">TOTAL DE HORAS SOLICITADAS</label>
							<input type="text" readonly value="{{$totalHorasPermitidas}}" class="form-control" id="totalHorasPermitidas">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script type="text/javascript">
		$(document).ready(function($) {
			$("#tipopermiso").change(function(){
				let tipo = $("#tipopermiso").val();
				console.log(tipo);
				if (tipo == '') {
					console.log('ninguno')
					$("#dia").addClass('d-none');
					$("#hora").addClass('d-none');
				}
				else {
					if (tipo == 'dia') {
						console.log('dia')
						$("#dia").removeClass('d-none');
						$("#hora").addClass('d-none');
						document.getElementById("fechainicio").required = true;
						document.getElementById("fechafin").required = true;
						document.getElementById("diastotales").required = true;
						document.getElementById("horainicio").required = false;
						document.getElementById("horafin").required = false;
						document.getElementById("horastotales").required = false;
						
					}
					if(tipo == 'hora'){
						console.log('hora')
						$("#hora").removeClass('d-none');
						$("#dia").addClass('d-none');

						document.getElementById("horainicio").required = true;
						document.getElementById("horafin").required = true;
						document.getElementById("horastotales").required = true;
						document.getElementById("fechainicio").required = false;
						document.getElementById("fechafin").required = false;
						document.getElementById("diastotales").required = false;
					} 
				}
			});
		});
	</script>
