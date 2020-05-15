<table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="tablaHistorialInventario">
    <thead>
        <tr class="info">
            <th>FECHA</th>
            <th>STOCK AÑADIDO TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($historialModificacionesInventario as $modificacion)
            <tr>
                <td>{{\Carbon\Carbon::parse($modificacion->fecha)->formatLocalized('%d de %B de %Y')}}</td>
                <td>{{$modificacion->Total}}</td>
                <td>
                    <a class="btn btn-warning" href="{{$modificacion->URL}}">
                        Ver más
                    </a>
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>    
</table>