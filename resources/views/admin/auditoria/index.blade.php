@extends('layouts.app')

@section('title', 'Registro de Auditoría y Sesiones')

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
                                <h2 class="h3 fw-bold mb-1 text-white">Auditoría y Sesiones en Vivo</h2>
                                <p class="mb-0 text-white-50">Historial inmutable de eventos y monitor en tiempo real de sesiones de usuarios conectados</p>
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

        {{-- Pestañas de Navegación: Historial vs Sesiones en Vivo --}}
        <ul class="nav nav-pills mb-4 p-1 bg-white rounded-4 shadow-sm border d-inline-flex" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 py-2 fw-semibold" id="pills-eventos-tab" data-bs-toggle="pill" data-bs-target="#pills-eventos" type="button" role="tab" aria-controls="pills-eventos" aria-selected="true">
                    <i class="bi bi-journal-text me-1"></i> Historial de Eventos
                    <span class="badge bg-primary rounded-pill ms-2">{{ $auditorias->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 py-2 fw-semibold position-relative" id="pills-sesiones-tab" data-bs-toggle="pill" data-bs-target="#pills-sesiones" type="button" role="tab" aria-controls="pills-sesiones" aria-selected="false">
                    <i class="bi bi-broadcast text-success me-1"></i> Sesiones en Vivo
                    <span class="badge bg-success rounded-pill ms-2">{{ count($activeSessions) }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            {{-- Pestaña 1: Historial de Auditoría --}}
            <div class="tab-pane fade show active" id="pills-eventos" role="tabpanel" aria-labelledby="pills-eventos-tab">
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
                                               placeholder="Ej: CREATE, UPDATE, DELETE, LOGIN..." value="{{ request('accion') }}">
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
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
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
                                            str_contains($actionUpper, 'DELETE') || str_contains($actionUpper, 'DESTROY') || str_contains($actionUpper, 'REVOKE') => 'bg-danger-subtle text-danger border border-danger-subtle',
                                            str_contains($actionUpper, 'FAILED') => 'bg-danger text-white',
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

            {{-- Pestaña 2: Monitor de Sesiones Activas en Tiempo Real --}}
            <div class="tab-pane fade" id="pills-sesiones" role="tabpanel" aria-labelledby="pills-sesiones-tab">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                                <span class="spinner-grow spinner-grow-sm text-success me-2" role="status"></span>
                                Sesiones de Usuario Conectadas en Tiempo Real
                            </h5>
                            <p class="text-muted small mb-0">Listado de usuarios con sesión abierta en la base de datos de COMESIS</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.auditoria.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-arrow-repeat me-1"></i> Refrescar Monitor
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Usuario</th>
                                    <th scope="col">Dispositivo / Agente</th>
                                    <th scope="col" class="text-center">Dirección IP</th>
                                    <th scope="col" class="text-center">Taquilla / ATM Asignada</th>
                                    <th scope="col">Última Actividad</th>
                                    <th scope="col" class="text-center pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeSessions as $ses)
                                    @php
                                        $isCurrent = ($ses->id === $currentSessionId);
                                        $lastActive = \Carbon\Carbon::createFromTimestamp($ses->last_activity);
                                        $isMobile = preg_match('/mobile|android|iphone|ipad/i', $ses->user_agent ?? '');
                                    @endphp
                                    <tr>
                                        {{-- Usuario --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                                     style="width: 38px; height: 38px; font-size: 14px;">
                                                    {{ strtoupper(substr($ses->user_name ?? 'A', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">
                                                        {{ $ses->user_name ?? 'Usuario Anónimo / Invitado' }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $ses->user_email ?? 'Sin correo' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Dispositivo --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi {{ $isMobile ? 'bi-phone' : 'bi-laptop' }} fs-5 text-secondary"></i>
                                                <span class="small text-muted text-truncate" style="max-width: 250px;" title="{{ $ses->user_agent }}">
                                                    {{ Str::limit($ses->user_agent, 45) }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- IP --}}
                                        <td class="text-center">
                                            <span class="badge bg-secondary-subtle text-primary font-monospace px-2 py-1 fs-7 border">
                                                {{ $ses->ip_address ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- ATM --}}
                                        <td class="text-center">
                                            @if($ses->atm_nombre)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 font-monospace">
                                                    <i class="bi bi-display me-1"></i> {{ $ses->atm_nombre }}
                                                </span>
                                            @else
                                                <span class="text-muted small">Sin taquilla</span>
                                            @endif
                                        </td>

                                        {{-- Última Actividad --}}
                                        <td>
                                            <span class="small fw-semibold text-dark d-block">
                                                {{ $lastActive->format('H:i:s') }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $lastActive->diffForHumans() }}
                                            </small>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-center pe-4">
                                            @if($isCurrent)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">
                                                    <i class="bi bi-person-check-fill me-1"></i> Tu Sesión
                                                </span>
                                            @else
                                                <form action="{{ route('admin.auditoria.revokeSession', $ses->id) }}" method="POST"
                                                      onsubmit="return confirm('¿Desconectar forzosamente la sesión de este usuario? Si tiene una taquilla asignada, será liberada.');" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm fw-semibold" title="Desconectar usuario y liberar recursos">
                                                        <i class="bi bi-box-arrow-right me-1"></i> Desconectar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-broadcast text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No hay sesiones activas</h5>
                                                <p class="text-muted small mb-0">Las conexiones concurrentes se mostrarán aquí en tiempo real.</p>
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
