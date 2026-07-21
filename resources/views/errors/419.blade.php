@extends('layouts.index')

@section('title', 'Página expirada (419)')

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <h1>Sesión expirada</h1>
            <p class="fs-5 text-warning">
                {!! $errorInfo ?? 'La página ha expirado. Por favor, vuelva a iniciar sesión e intente nuevamente.' !!}
            </p>

            <a href="{{ route('login') }}" class="btn btn-primary">Ir al login</a>
        </div>
    </div>
@endsection
