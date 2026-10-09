@extends('layouts.app')

@section('title', 'Lista Blanca de IPs')

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
                                    <i class="bi bi-shield-check me-1"></i> Seguridad Perimetral
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Lista Blanca de IPs</h2>
                                <p class="mb-0 text-white-50">Control de acceso por red: autoriza direcciones IP exclusivas para ingresar al sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta de Registro de Nueva IP --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="bi bi-plus-circle text-primary me-2"></i>Autorizar Nueva Dirección IP
                        </h6>
                        <form action="{{ route('admin.allowed-ips.store') }}" method="POST" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-secondary mb-1">Dirección IP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-hdd-network text-primary"></i>
                                    </span>
                                    <input type="text" name="ip_address" class="form-control border-secondary-subtle font-monospace" 
                                           placeholder="Ej: 192.168.1.100 o 10.0.0.1" required>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label small fw-semibold text-secondary mb-1">Descripción / Ubicación</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-card-text text-primary"></i>
                                    </span>
                                    <input type="text" name="description" class="form-control border-secondary-subtle" 
                                           placeholder="Ej: Taquilla Comedor Central, Servidor Local...">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-primary w-100 rounded-pill fw-semibold shadow-sm" type="submit">
                                    <i class="bi bi-check-circle-fill me-1"></i> Agregar a Lista Blanca
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de IPs --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ count($ips) }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Direcciones IP Autorizadas</h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Dirección IP</th>
                                    <th scope="col">Descripción / Dispositivo</th>
                                    <th scope="col" class="text-center">Estado</th>
                                    <th scope="col" class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ips as $ip)
                                    <tr>
                                        {{-- IP --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                     style="width: 38px; height: 38px; background-color: #0d6efd; font-size: 14px;">
                                                    <i class="bi bi-router"></i>
                                                </div>
                                                <span class="badge bg-secondary-subtle text-primary font-monospace px-3 py-2 fs-7 border">
                                                    {{ $ip->ip_address }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Descripción --}}
                                        <td>
                                            <span class="text-dark fw-medium">
                                                {{ $ip->description ?: 'Sin descripción' }}
                                            </span>
                                        </td>

                                        {{-- Estado --}}
                                        <td class="text-center">
                                            @if($ip->status)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> ACTIVA
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                                    <i class="bi bi-x-circle-fill me-1"></i> INACTIVA
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-center pe-4">
                                            <div class="d-inline-flex gap-2 align-items-center justify-content-center">
                                                {{-- Toggle status --}}
                                                <form action="{{ route('admin.allowed-ips.update', $ip) }}" method="POST" class="d-inline m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $ip->status ? 0 : 1 }}">
                                                    <button class="btn btn-sm {{ $ip->status ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-pill px-3 py-1 shadow-2xs" 
                                                            type="submit" title="{{ $ip->status ? 'Desactivar acceso' : 'Activar acceso' }}">
                                                        <i class="bi {{ $ip->status ? 'bi-slash-circle' : 'bi-check-circle' }} me-1"></i>
                                                        {{ $ip->status ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>

                                                {{-- Eliminar --}}
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                        style="width: 32px; height: 32px;" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEliminarIp{{ $ip->id }}" 
                                                        title="Eliminar IP">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            {{-- Modal de Confirmación de Eliminación --}}
                                            <div class="modal fade text-start" id="modalEliminarIp{{ $ip->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <div class="modal-header bg-danger text-white py-3">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar IP Autorizada
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            ¿Está seguro de eliminar la IP <strong class="text-dark font-monospace">{{ $ip->ip_address }}</strong> de la lista blanca?
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('admin.allowed-ips.destroy', $ip) }}" method="POST" class="m-0">
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
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-shield-x text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No hay IPs en lista blanca</h5>
                                                <p class="text-muted small mb-0">Agrega las IPs autorizadas para restringir el acceso a los terminales.</p>
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
