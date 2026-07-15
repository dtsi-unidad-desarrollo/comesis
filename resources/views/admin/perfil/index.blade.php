@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
<div class="container">
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset(Auth::user()->foto) }}" alt="Foto de perfil" class="rounded-circle" width="120" height="120">
                        <h5 class="mt-3 mb-1">{{ $usuario->nombre }}</h5>
                        <p class="text-muted mb-0">Actualiza tu información de acceso</p>
                    </div>

                    <form action="{{ route('admin.perfil.update') }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-12">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
                            @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Usuario o correo electrónico</label>
                            <input type="text" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contraseña actual</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Solo si deseas cambiarla">
                            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres">
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva contraseña">
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-3">
                            <a href="{{ route('admin.panel.index') }}" class="btn btn-outline-secondary">Volver</a>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
