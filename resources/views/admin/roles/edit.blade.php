@extends('layouts.app')

@section('title', 'Editar Rol')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div class="col-12 mb-3">
        <h2>Editar rol</h2>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $role->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estatus</label>
                        <select name="estatus" class="form-select">
                            <option value="1" {{ old('estatus', $role->estatus) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estatus', $role->estatus) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permisos asignados</label>
                        <div class="row">
                            @foreach($permisos as $permiso)
                                <div class="col-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permisos[]" value="{{ $permiso->id }}" id="permiso-{{ $permiso->id }}" {{ in_array($permiso->id, old('permisos', $role->permisos->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permiso-{{ $permiso->id }}">{{ $permiso->nombre }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
