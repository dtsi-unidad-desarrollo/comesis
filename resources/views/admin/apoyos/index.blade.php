@extends('layouts.app')

@section('title', 'Apoyos Institucionales')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Apoyos Institucionales</h1>
                <a href="{{ route('admin.apoyos.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nuevo Apoyo
                </a>
            </div>
        </div>

        @include('partials.alert')


        <div class="table-responsive">
            <table class="table table-hover bg-white mt-2">
                <thead>
                    <tr class="bg-primary text-white">
                        <th scope="col">#</th>
                        <th scope="col">Institución</th>
                        <th scope="col">Código</th>
                        <th scope="col">Habilitadas</th>
                        <th scope="col">Consumidas</th>
                        <th scope="col">Disponibles</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($apoyos as $apoyo)
                        <tr>
                            <td scope="row">{{ $apoyo->id }}</td>
                            <td>{{ $apoyo->institucion }}</td>
                            <td>{{ $apoyo->codigo }}</td>
                            <td class="text-center">{{ $apoyo->cantidad_habilitada }}</td>
                            <td class="text-center">{{ $apoyo->cantidad_consumida }}</td>
                            <td
                                class="text-center fw-bold {{ ($apoyo->cantidad_habilitada - $apoyo->cantidad_consumida) <= 0 ? 'table-danger' : 'table-success' }}">
                                {{ $apoyo->cantidad_habilitada - $apoyo->cantidad_consumida }}
                            </td>
                            <td class="{{ $apoyo->estatus ? 'table-success' : 'table-danger' }}">
                                {{ $apoyo->estatus ? 'ACTIVO' : 'INACTIVO' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.apoyos.ticket', $apoyo->id) }}" target="_blank"
                                    class="text-primary fs-5" title="Imprimir Ticket QR">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <a href="{{ route('admin.apoyos.edit', $apoyo->id) }}" class="text-warning fs-5 ms-2"
                                    title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.apoyos.destroy', $apoyo->id) }}" method="POST"
                                    class="d-inline-block ms-2"
                                    onsubmit="return confirm('¿Está seguro de eliminar este apoyo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent text-danger fs-5 p-0" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay apoyos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $apoyos->links() }}
        </div>

    </div>
@endsection