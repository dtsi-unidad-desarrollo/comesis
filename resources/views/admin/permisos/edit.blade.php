@extends('layouts.app')

@section('title', 'Editar Permiso')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div class="col-12 mb-3">
        <h2>Editar permiso</h2>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.permisos.update', $permiso->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $permiso->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estatus</label>
                        <select name="estatus" class="form-select">
                            <option value="1" {{ old('estatus', $permiso->estatus) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estatus', $permiso->estatus) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('admin.permisos.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
