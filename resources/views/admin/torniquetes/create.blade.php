@extends('layouts.app')

@section('title', 'Crear Torniquete')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
            <h2>Crear Torniquete</h2>
            <a href="{{ route('admin.torniquetes.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="col-12">
            <form method="POST" action="{{ route('admin.torniquetes.store') }}">
                @include('admin.torniquetes._form')
            </form>
        </div>
    </div>
</div>
@endsection
