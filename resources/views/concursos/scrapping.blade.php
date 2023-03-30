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

    <form action="{{ route('data') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="fecha_inicio_publicacion">Fecha inicial de Publicación</label>
                    <input type="date" class="form-control" name="fecha_inicio_publicacion" id="fecha_inicio_publicacion">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                <label for="fecha_inicio_publicacion">Fecha final de Publicación</label>
                    <input type="date" class="form-control" name="fecha_final_publicacion" id="fecha_final_publicacion">
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <img src="data:image/png;base64,{{ $image_captcha }} " alt="">
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <div class="form-group">
                    <input type="hidden" name="cookie" value="{{$cookie}}">
                    <input type="hidden" name="view_state" value="{{$view_state}}">
                    <input type="text" class="form-control" name="captcha" placeholder="Ingresa el captcha" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>

        </div>

    </form>

@endsection