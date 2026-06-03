@extends('layouts.app')

@section('title', 'Detalle del Torniquete')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
            <h2>Detalle del Torniquete</h2>
            <a href="{{ route('admin.torniquetes.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>

        <div class="card">
            <div class="card-body">
                <p><strong>ID:</strong> {{ $torniquete->id }}</p>
                <p><strong>Nombre:</strong> {{ $torniquete->nombre }}</p>
                <p><strong>Tipo:</strong> {{ $torniquete->tipo ?? '-' }}</p>
                <p><strong>Estatus:</strong> {{ $torniquete->estatus ? 'ACTIVO' : 'INACTIVO' }}</p>
                <p><strong>Endpoint URL:</strong> {{ $torniquete->endpoint_url ?? '-' }}</p>
                <p><strong>Descripción:</strong> {{ $torniquete->descripcion ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
