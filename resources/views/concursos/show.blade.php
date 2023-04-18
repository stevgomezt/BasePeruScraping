@extends('layout')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h2 class="text-center">Concurso: {{$convocatoria}}</h2>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif

@if(sizeof($detalle_concurso) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-dark mb-3">
                <div class="card-header">
                    <h4>Información General</h4>
                </div>
                <div class="card-body">
                    @foreach($detalle_concurso as $dc)
                        @if($dc->tipo_id == 1)
                            <p><strong>{{ utf8_encode($dc->key) }} </strong>{{ utf8_encode($dc->value) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-dark mb-3">
                <div class="card-header">
                    <h4>Información general de la Entidad</h4>
                </div>
                <div class="card-body">
                    @foreach($detalle_concurso as $dc)
                        @if($dc->tipo_id == 2)
                            <p><strong>{{$dc->key}} </strong>{{$dc->value}}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-dark mb-3">
                <div class="card-header">
                    <h4>Información general del procedimiento</h4>
                </div>
                <div class="card-body">
                    @foreach($detalle_concurso as $dc)
                        @if($dc->tipo_id == 3)
                            <p><strong>{{$dc->key}} </strong>{{$dc->value}}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-6">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="col-12">
                <div class="card border-dark mb-3">
                    <div class="card-header">
                        <h4>Documentos por Etapa</h4>
                    </div>
                    <div class="card-body">


                    <table class="table">
    <thead>
        <tr>
            <th scope="col">Nro</th>
            <th scope="col">Etapa</th>
            <th scope="col">Documento</th>
            <th scope="col">Archivo</th>
            <th scope="col">Fecha y Hora de publicación</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documentos as $documento)
            <tr>
                @foreach($documento->value as $key => $value)
                    <td class="archivo">{{$value}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    var archivoCells = document.getElementsByClassName("archivo");
    for (var i = 0; i < archivoCells.length; i++) {
        var archivoCell = archivoCells[i];
        archivoCell.innerHTML = "<a href='" + archivoCell.innerHTML + "'>" + archivoCell.innerHTML + "</a>";
    }
</script>




                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-alert">Start Adding to the Database
@endif

@endsection
