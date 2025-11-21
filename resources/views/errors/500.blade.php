@extends('layouts.index')

@section('title', 'Error del servidor')

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <h1>Información de error del servidor:</h1>
            <p class="fs-5 text-danger">
                {!! $errorInfo !!}
            </p>

            <a href="{{ url()->previous() }}" class="btn btn-primary">Volver al panel</a>
        </div>
    </div>
    
@endsection