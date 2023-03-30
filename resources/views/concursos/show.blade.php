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
        <div class="col-6">
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
        </div>
        <div class="col-6">
            <div class="col-12">
                <div class="card border-dark mb-3">
                    <div class="card-header">
                        <h4>Cronograma</h4>
                    </div>
                    <div class="card-body">
                        @foreach($detalle_concurso as $dc)
                            @if($dc->tipo_id == 4)
                                <p><strong>{{$dc->key}} </strong>{{$dc->value}}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card border-dark mb-3">
                    <div class="card-header">
                        <h4>Entidad Contratante</h4>
                    </div>
                    <div class="card-body">
                        @foreach($detalle_concurso as $dc)
                            @if($dc->tipo_id == 5)
                                <p><strong>{{$dc->key}} </strong>{{$dc->value}}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
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
                                    @foreach($documentos as $documento)
                                    <tbody>
                                    <tr>
                                        @foreach($documento->value as $item)
                                            <td>{{$item}}</td>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card border-dark mb-3">
                            <div class="card-header">
                                <h4>Opciones del Procedimiento</h4>
                            </div>
                            <div class="card-body">
                                <table>
                                    <thead>
                                        <tr>
                                        @foreach($botoneria_imagenes as $dc)
                                                <td style="width:8.3%;">
                                                    <a href="/concurso/botoneria/{{$convocatoria}}/{{$dc->value}}" class="btn btn-success">Ver</a>
                                                </td>
                                        @endforeach
                                        </tr>
                                        <tr>
                                        @foreach($botoneria_enlaces as $dc)
                                                <td style="width:8.3%;">
                                                    <a href="/concurso/botoneria/{{$convocatoria}}/{{$dc->value}}">{{$dc->key}}</a>
                                                </td>
                                        @endforeach
                                        </tr>
                                    </thead>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    @else
        <div class="alert alert-alert">Start Adding to the Database.</div>
    @endif

    

@endsection