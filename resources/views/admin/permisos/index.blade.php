@extends('layouts.app')

@section('title', 'Permisos y Accesos')

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
                    <strong>¡Atención!</strong> Se encontraron errores:
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
                                    <i class="bi bi-key-fill me-1"></i> Matriz de Privilegios
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Permisos del Sistema</h2>
                                <p class="mb-0 text-white-50">Gestiona los permisos atómicos asignables a los roles de usuario</p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalCrearPermiso">
                                    <i class="bi bi-plus-circle-fill me-2 fs-5"></i>
                                    <span>Crear Permiso</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Permisos --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ $permisos->total() }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Permisos Disponibles</h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Permiso</th>
                                    <th scope="col" class="text-center">Estatus</th>
                                    <th scope="col" class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permisos as $permiso)
                                    <tr>
                                        {{-- Nombre del Permiso --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                     style="width: 40px; height: 40px; background-color: #0d6efd; font-size: 16px;">
                                                    <i class="bi bi-key"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0 font-monospace">
                                                        {{ $permiso->nombre }}
                                                    </div>
                                                    <small class="text-muted">
                                                        ID #{{ $permiso->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Estatus --}}
                                        <td class="text-center">
                                            @if ($permiso->estatus == 1)
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
                                                <a href="{{ route('admin.permisos.edit', $permiso->id) }}" 
                                                   class="btn btn-sm btn-outline-warning rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;" 
                                                   title="Editar permiso">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                {{-- Eliminar --}}
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                        style="width: 32px; height: 32px;" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEliminarPermiso{{ $permiso->id }}" 
                                                        title="Eliminar permiso">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            {{-- Modal de Confirmación de Eliminación --}}
                                            <div class="modal fade text-start" id="modalEliminarPermiso{{ $permiso->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <div class="modal-header bg-danger text-white py-3">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Permiso
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            ¿Está seguro de eliminar el permiso <strong class="text-dark font-monospace">{{ $permiso->nombre }}</strong>?
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('admin.permisos.destroy', $permiso->id) }}" method="POST" class="m-0">
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
                                        <td colspan="3" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-key text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No hay permisos registrados</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer de Tabla y Paginación --}}
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="text-muted small">
                                Mostrando <strong>{{ $permisos->firstItem() ?? 0 }}</strong> a <strong>{{ $permisos->lastItem() ?? 0 }}</strong> de <strong>{{ $permisos->total() }}</strong> permisos
                            </div>
                            <div>
                                {{ $permisos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Crear Permiso -->
    <div class="modal fade text-start" id="modalCrearPermiso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCrearPermisoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold" id="modalCrearPermisoLabel">
                        <i class="bi bi-key-fill me-2"></i>Crear Nuevo Permiso
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.permisos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small mb-1">Identificador del Permiso</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control border-secondary-subtle font-monospace" placeholder="Ej: reportes, auditoria..." required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small mb-1">Estatus</label>
                            <select name="estatus" class="form-select border-secondary-subtle">
                                <option value="1" selected>ACTIVO</option>
                                <option value="0">INACTIVO</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i> Guardar Permiso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
