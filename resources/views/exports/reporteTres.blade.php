 <table class="table table-hover table-striped table-bordered" style="margin-bottom: 0;" id="listaEmpleados">
                    <thead>
                        <tr class="info">
                            <th>Fecha</th>
                            <th> Pacientes 1 prenda</th>
                            <th># Prendas > 1 prenda</th>
                        </tr>
                    </thead>
                    <tbody>
                            @for ($i = 0; $i < count($arregloFechasConVentas); $i++)
                                
                                <tr>
                                    <td>{{$arregloFechasConVentas[$i]}}</td>
                                    
                                    
                                </tr>
                                <tr>
                                    <td>{{$arregloTotalPacientesConUnProducto[$i]}}</td>
                                </tr>
                                <tr>
                                    
                                    <td>{{$arregloTotalPacientesConMasDeUnProducto[$i]}}</td>
                                   
                                </tr>
                            @endfor
                       
                    </tbody>    
                </table>