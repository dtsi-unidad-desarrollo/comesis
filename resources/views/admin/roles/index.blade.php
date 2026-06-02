@extends('layouts.app')

@section('title', 'Roles')

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
        <h2>Roles</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearRol">
            Crear Rol
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
                            <th>Permisos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->nombre }}</td>
                                <td class="{{ $role->estatus == 1 ? 'table-success' : 'table-danger' }}">
                                    {{ $role->estatus == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                </td>
                                <td>
                                    @foreach($role->permisos as $permiso)
                                        <span class="badge bg-secondary">{{ $permiso->nombre }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar rol?');">
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
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </section>

    @include('admin.roles.partials.modalCrear')

@endsection
