@extends('layouts.app')

@section('title', 'Permisos')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-start">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <h2>Permisos</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearPermiso">
            Crear Permiso
        </button>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12 table-responsive">
                <table class="table table-hover bg-white mt-2">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permisos as $permiso)
                            <tr>
                                <td>{{ $permiso->id }}</td>
                                <td>{{ $permiso->nombre }}</td>
                                <td class="{{ $permiso->estatus == 1 ? 'table-success' : 'table-danger' }}">
                                    {{ $permiso->estatus == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.permisos.edit', $permiso->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                                    <form action="{{ route('admin.permisos.destroy', $permiso->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar permiso?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $permisos->links() }}
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-start" id="modalCrearPermiso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCrearPermisoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCrearPermisoLabel">Crear nuevo permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.permisos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estatus</label>
                            <select name="estatus" class="form-select">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear permiso</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
