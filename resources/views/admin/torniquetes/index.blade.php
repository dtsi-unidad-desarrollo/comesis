@extends('layouts.app')

@section('title', 'Torniquetes')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <h2>Administrar Torniquetes</h2>
        <a href="{{ route('admin.torniquetes.create') }}" class="btn btn-primary">Crear Torniquete</a>
    </div>

    <div class="col-lg-12 table-responsive">
        <table class="table table-hover bg-white mt-2">
            <thead>
                <tr class="bg-primary text-white">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Estatus</th>
                    <th>Endpoint</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($torniquetes->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">No hay torniquetes registrados.</td>
                    </tr>
                @else
                    @foreach ($torniquetes as $torniquete)
                        <tr>
                            <td>{{ $torniquete->id }}</td>
                            <td>{{ $torniquete->nombre }}</td>
                            <td>{{ $torniquete->tipo ?? '-' }}</td>
                            <td class="{{ $torniquete->estatus ? 'table-success' : 'table-danger' }}">
                                {{ $torniquete->estatus ? 'ACTIVO' : 'INACTIVO' }}
                            </td>
                            <td>{{ $torniquete->endpoint_url ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.torniquetes.edit', $torniquete->id) }}"
                                    class="btn btn-sm btn-secondary">Editar</a>
                                <form action="{{ route('admin.torniquetes.destroy', $torniquete->id) }}" method="POST"
                                    style="display:inline-block" onsubmit="return confirm('¿Eliminar torniquete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        {{-- <div class="mt-3">
            {{ $torniquetes->links() }}
        </div> --}}
    </div>

@endsection
