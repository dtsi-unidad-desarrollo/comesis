@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

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
                    <strong>¡Atención!</strong> Se encontraron errores en la solicitud:
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
                                    <i class="bi bi-shield-lock-fill me-1"></i> Control de Acceso
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Gestión de Usuarios</h2>
                                <p class="mb-0 text-white-50">Administra las cuentas de operadores, cajeros y personal con acceso al sistema</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.create') }}" 
                                   class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center">
                                    <i class="bi bi-person-plus-fill me-2 fs-5"></i>
                                    <span>Nuevo Usuario</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjetas KPI de Resumen --}}
        @php
            $totalUsuarios = count($usuarios);
            $totalAdmins = collect($usuarios)->filter(function($u) {
                return strtoupper($u->rol->nombre ?? '') === 'ADMINISTRADOR';
            })->count();
            $totalCajeros = collect($usuarios)->filter(function($u) {
                return strtoupper($u->rol->nombre ?? '') === 'CAJERO';
            })->count();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-primary">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Total Usuarios</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalUsuarios }}</h3>
                            <small class="text-primary fw-semibold">Cuentas activas</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-info">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-shield-check fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Administradores</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalAdmins }}</h3>
                            <small class="text-info fw-semibold">Acceso total</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-success">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Cajeros / Taquilla</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalCajeros }}</h3>
                            <small class="text-success fw-semibold">Operadores de acceso</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Usuarios --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ $totalUsuarios }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Usuarios Registrados</h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Usuario</th>
                                    <th scope="col">Usuario / Email</th>
                                    <th scope="col">Rol Asignado</th>
                                    <th scope="col" class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($usuarios as $usuario)
                                    @php
                                        $fotoValida = $usuario->foto && file_exists(public_path($usuario->foto));
                                        $inicial = strtoupper(substr(trim($usuario->nombre ?? 'U'), 0, 1));
                                        $rolUpper = strtoupper($usuario->rol->nombre ?? '');
                                        $badgeRol = match($rolUpper) {
                                            'ADMINISTRADOR', 'ROOT' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                            'CAJERO' => 'bg-success-subtle text-success border border-success-subtle',
                                            default => 'bg-secondary-subtle text-secondary border border-secondary-subtle'
                                        };
                                    @endphp
                                    <tr>
                                        {{-- Usuario con Avatar --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if ($fotoValida)
                                                    <img src="{{ asset($usuario->foto) }}" alt="Foto" 
                                                         class="rounded-circle shadow-sm me-3 flex-shrink-0" 
                                                         style="width: 42px; height: 42px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                         style="width: 42px; height: 42px; background-color: #0d6efd; font-size: 15px;">
                                                        {{ $inicial }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">
                                                        {{ $usuario->nombre }}
                                                    </div>
                                                    <small class="text-muted">
                                                        ID #{{ $usuario->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Email / Username --}}
                                        <td>
                                            <span class="badge bg-secondary-subtle text-primary font-monospace px-2 py-1 fs-7 border">
                                                <i class="bi bi-person me-1"></i>{{ $usuario->email }}
                                            </span>
                                        </td>

                                        {{-- Rol --}}
                                        <td>
                                            <span class="badge {{ $badgeRol }} px-2 py-1 fw-bold">
                                                <i class="bi bi-shield-lock me-1"></i>{{ $usuario->rol->nombre ?? 'Sin Rol' }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-center pe-4">
                                            <div class="d-inline-flex gap-1 align-items-center justify-content-center">
                                                {{-- Editar --}}
                                                <a href="{{ route('admin.users.edit', $usuario->id) }}" 
                                                   class="btn btn-sm btn-outline-warning rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;" 
                                                   title="Editar usuario">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                {{-- Eliminar con Modal --}}
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                        style="width: 32px; height: 32px;" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEliminarUsuario{{ $usuario->id }}" 
                                                        title="Eliminar usuario">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            {{-- Modal de Confirmación de Eliminación --}}
                                            <div class="modal fade text-start" id="modalEliminarUsuario{{ $usuario->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <div class="modal-header bg-danger text-white py-3">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Usuario
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            ¿Está seguro de eliminar al usuario <strong class="text-dark">{{ $usuario->nombre }}</strong> ({{ $usuario->email }})?
                                                            <p class="small text-danger mt-2 mb-0">
                                                                <i class="bi bi-info-circle me-1"></i> Esta acción no se puede deshacer y revocará inmediatamente sus accesos.
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('admin.users.destroy', $usuario->id) }}" method="post" class="m-0">
                                                                @csrf
                                                                @method('delete')
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
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-people text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No hay usuarios registrados</h5>
                                                <p class="text-muted small mb-0">Crea el primer usuario para asignar accesos.</p>
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
