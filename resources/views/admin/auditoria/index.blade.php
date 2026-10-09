@extends('layouts.app')

@section('title', 'Registro de Auditoría')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

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
                                    <i class="bi bi-shield-check me-1"></i> Trazabilidad y Seguridad
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Registro de Auditoría</h2>
                                <p class="mb-0 text-white-50">Historial inmutable de eventos, modificaciones y acciones realizadas en el sistema</p>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <a href="{{ route('admin.auditoria.export.excel', request()->query()) }}" 
                                   class="btn btn-light bg-opacity-25 text-white border-0 rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
                                    <i class="bi bi-file-earmark-excel text-success me-2"></i>
                                    <span>Exportar Excel</span>
                                </a>
                                <a href="{{ route('admin.auditoria.export.pdf', request()->query()) }}" target="_blank"
                                   class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                    <span>Reporte PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta de Filtros --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-person text-primary me-1"></i> Usuario
                                </label>
                                <input type="text" name="usuario" class="form-control border-secondary-subtle" 
                                       placeholder="Filtrar por usuario..." value="{{ request('usuario') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-lightning text-primary me-1"></i> Acción
                                </label>
                                <input type="text" name="accion" class="form-control border-secondary-subtle" 
                                       placeholder="Ej: CREATE, UPDATE, DELETE..." value="{{ request('accion') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-calendar-event text-primary me-1"></i> Desde
                                </label>
                                <input type="date" name="fecha_desde" class="form-control border-secondary-subtle" 
                                       value="{{ request('fecha_desde') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-calendar-check text-primary me-1"></i> Hasta
                                </label>
                                <input type="date" name="fecha_hasta" class="form-control border-secondary-subtle" 
                                       value="{{ request('fecha_hasta') }}">
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center shadow-sm" type="submit">
                                    <i class="bi bi-funnel-fill me-1"></i> Filtrar
                                </button>
                                @if(request()->hasAny(['usuario', 'accion', 'fecha_desde', 'fecha_hasta']))
                                    <a href="{{ route('admin.auditoria.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="Limpiar Filtros">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Auditoría --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ $auditorias->total() }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Eventos Registrados</h5>
                            @if(request()->hasAny(['usuario', 'accion', 'fecha_desde', 'fecha_hasta']))
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-2">
                                    <i class="bi bi-funnel-fill me-1"></i> Filtro activo
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Fecha / Hora</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col" class="text-center">Acción</th>
                                    <th scope="col">Entidad / Módulo</th>
                                    <th scope="col">Detalle del Evento</th>
                                    <th scope="col" class="text-center pe-4">Dirección IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditorias as $registro)
                                    @php
                                        $actionUpper = strtoupper($registro->action ?? '');
                                        $badgeAction = match(true) {
                                            str_contains($actionUpper, 'CREATE') || str_contains($actionUpper, 'STORE') => 'bg-success-subtle text-success border border-success-subtle',
                                            str_contains($actionUpper, 'UPDATE') || str_contains($actionUpper, 'EDIT') => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                            str_contains($actionUpper, 'DELETE') || str_contains($actionUpper, 'DESTROY') => 'bg-danger-subtle text-danger border border-danger-subtle',
                                            str_contains($actionUpper, 'LOGIN') => 'bg-info-subtle text-info border border-info-subtle',
                                            default => 'bg-primary-subtle text-primary border border-primary-subtle'
                                        };
                                    @endphp
                                    <tr>
                                        {{-- Fecha y Hora --}}
                                        <td class="ps-4">
                                            <div class="fw-semibold text-dark mb-0 font-monospace" style="font-size: 13px;">
                                                {{ $registro->created_at->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted font-monospace">
                                                {{ $registro->created_at->format('H:i:s') }}
                                            </small>
                                        </td>

                                        {{-- Usuario --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light text-primary fw-bold d-flex align-items-center justify-content-center me-2 flex-shrink-0"
                                                     style="width: 32px; height: 32px; font-size: 13px;">
                                                    {{ strtoupper(substr($registro->user_name ?? 'S', 0, 1)) }}
                                                </div>
                                                <span class="fw-semibold text-dark">
                                                    {{ $registro->user_name ?? 'Sistema' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Acción --}}
                                        <td class="text-center">
                                            <span class="badge {{ $badgeAction }} px-2 py-1 font-monospace fw-bold">
                                                {{ $registro->action }}
                                            </span>
                                        </td>

                                        {{-- Entidad --}}
                                        <td>
                                            <span class="badge bg-light text-secondary border px-2 py-1">
                                                {{ $registro->entity_type ?? '-' }} {{ $registro->entity_id ? '#'.$registro->entity_id : '' }}
                                            </span>
                                        </td>

                                        {{-- Detalle --}}
                                        <td style="max-width: 320px;">
                                            <div class="text-truncate small text-secondary" title="{{ $registro->details }}">
                                                {{ $registro->details ? (json_decode($registro->details, true)['message'] ?? $registro->details) : '-' }}
                                            </div>
                                        </td>

                                        {{-- IP --}}
                                        <td class="text-center pe-4">
                                            <span class="badge bg-secondary-subtle text-primary font-monospace px-2 py-1 fs-7 border">
                                                {{ $registro->ip_address ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-journal-text text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No hay registros de auditoría</h5>
                                                <p class="text-muted small mb-0">Los eventos de sistema se registrarán aquí automáticamente.</p>
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
                                Mostrando <strong>{{ $auditorias->firstItem() ?? 0 }}</strong> a <strong>{{ $auditorias->lastItem() ?? 0 }}</strong> de <strong>{{ $auditorias->total() }}</strong> eventos
                            </div>
                            <div>
                                {{ $auditorias->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
