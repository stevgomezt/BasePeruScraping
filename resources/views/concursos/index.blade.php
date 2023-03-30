@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center">Concursos</h2>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    @if(sizeof($concursos) > 0)
    <div class="row col-12 mb-4 mt-4">
        <label for="busquedaInput">Buscar por palabra clave:</label>
        <input class="busqueda form-control" id="busquedaInput" type="text" placeholder="Escribir palabra clave" />
    </div>
    
    <div style="overflow-x:auto;" class="row col-12">
        <table class="table table-bordered">
            <tr>
                <th>Convocatoria</th>
                <th>Nombre o Sigla de la Entidad</th>
                <th>Fecha y Hora de Publicacion</th>
                <th>Nomenclatura</th>
                <th>Reiniciado Desde</th>
                <th>Objeto de Contratación</th>
                <th>Descripción de Objeto</th>
                <th>Código SNIP</th>
                <th>Código Unico de Inversion</th>
                <th>Valor Referencial / Valor Estimado</th>
                <th>Moneda</th>
                <th>Versión SEACE</th>
                <th>Detalle</th>
            </tr>
            @foreach ($concursos as $concurso)
            <tbody id="tbody">
                <tr>
                    <td>{{ $concurso->convocatoria }}</td>
                    <td>{{ $concurso->nombre_o_sigla_de_la_entidad }}</td>
                    <td>{{ $concurso->fecha_y_hora_de_publicacion }}</td>
                    <td>{{ $concurso->nomenclatura }}</td>
                    <td>{{ $concurso->reiniciado_desde }}</td>
                    <td>{{ $concurso->objeto_de_contratacion }}</td>
                    <td>{{ $concurso->descripcion_de_objeto }}</td>
                    <td></td>
                    <td></td>
                    <td>{{ $concurso->valor_estimado }}</td>
                    <td>{{ $concurso->moneda }}</td>
                    <td>{{ $concurso->version_seace }}</td>
                    <td><a href="/concurso/{{$concurso->convocatoria}}" class="btn btn-primary">Ver</a></td>
                </tr>
            </tbody>
            @endforeach
        </table>
        </div>
    @else
        <div class="alert alert-alert">Start Adding to the Database.</div>
    @endif

    

@endsection