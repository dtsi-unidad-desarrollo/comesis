@extends('layouts.app')

@section('title', 'Torniquetes Físicos')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

    {{-- Respuesta de validaciones --}}
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-start shadow-sm mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                <div>
                    <strong>¡Atención!</strong> Por favor verifica los siguientes campos:
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        {{-- Banner Principal --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-door-open-fill me-1"></i> Control de Acceso Físico
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Torniquetes Físicos</h2>
                                <p class="mb-0 text-white-50">Gestiona los torniquetes automatizados, puntos de relé y endpoints de apertura</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.torniquetes.create') }}" 
                                   class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center">
                                    <i class="bi bi-plus-circle-fill me-2 fs-5"></i>
                                    <span>Nuevo Torniquete</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Torniquetes --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ count($torniquetes) }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Dispositivos Configurados</h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Torniquete</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Endpoint de Comunicación</th>
                                    <th scope="col" class="text-center">Estatus</th>
                                    <th scope="col" class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($torniquetes as $torniquete)
                                    <tr>
                                        {{-- Nombre con Avatar --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                     style="width: 40px; height: 40px; background-color: #0d6efd; font-size: 16px;">
                                                    <i class="bi bi-door-open"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">
                                                        {{ $torniquete->nombre }}
                                                    </div>
                                                    <small class="text-muted">
                                                        ID #{{ $torniquete->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Tipo --}}
                                        <td>
                                            <span class="badge bg-light text-secondary border px-2 py-1">
                                                {{ $torniquete->tipo ?? 'Estándar' }}
                                            </span>
                                        </td>

                                        {{-- Endpoint URL --}}
                                        <td>
                                            @if($torniquete->endpoint_url)
                                                <span class="badge bg-secondary-subtle text-primary font-monospace px-2 py-1 fs-7 border">
                                                    {{ $torniquete->endpoint_url }}
                                                </span>
                                            @else
                                                <span class="text-muted small">No configurado</span>
                                            @endif
                                        </td>

                                        {{-- Estatus --}}
                                        <td class="text-center">
                                            @if ($torniquete->estatus)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> ACTIVO
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                                    <i class="bi bi-x-circle-fill me-1"></i> INACTIVO
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-center pe-4">
                                            <div class="d-inline-flex gap-1 align-items-center justify-content-center">
                                                {{-- Editar --}}
                                                <a href="{{ route('admin.torniquetes.edit', $torniquete->id) }}" 
                                                   class="btn btn-sm btn-outline-warning rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;" 
                                                   title="Editar torniquete">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                {{-- Eliminar --}}
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                        style="width: 32px; height: 32px;" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEliminarTorniquete{{ $torniquete->id }}" 
                                                        title="Eliminar torniquete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            {{-- Modal de Confirmación de Eliminación --}}
                                            <div class="modal fade text-start" id="modalEliminarTorniquete{{ $torniquete->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <div class="modal-header bg-danger text-white py-3">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Torniquete
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            ¿Está seguro de eliminar el torniquete <strong class="text-dark">{{ $torniquete->nombre }}</strong>?
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('admin.torniquetes.destroy', $torniquete->id) }}" method="POST" class="m-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">
                                                                    Sí, Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-door-closed text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No hay torniquetes registrados</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
