@extends('layout')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center">Convocatoria: {{$convocatoria}}</h2>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <form action="{{ route('botoneria_data') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-2 col-md-2">
                <div class="form-group">
                    <img src="data:image/png;base64,{{ $image_captcha }} " alt="">
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <div class="form-group">
                    <input type="hidden" name="tb_ficha" value="{{$tb_ficha}}">
                    <input type="hidden" name="convocatoria" value="{{$convocatoria}}">
                    <input type="hidden" name="cookie" value="{{$cookie}}">
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