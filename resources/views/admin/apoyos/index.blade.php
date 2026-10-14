@extends('layouts.app')

@section('title', 'Apoyos Institucionales')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

    {{-- Respuesta de validaciones generales --}}
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-start shadow-sm mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                <div>
                    <strong>¡Atención!</strong> Se encontraron inconsistencias en la solicitud:
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
        {{-- Encabezado Principal con Banner Institucional --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-ticket-detailed-fill me-1"></i> Convenios Institucionales
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Apoyos Institucionales</h2>
                                <p class="mb-0 text-white-50">Control de cupos especiales autorizados, emisión de tickets con código QR y consumo en taquilla</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.apoyos.create') }}" 
                                   class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center">
                                    <i class="bi bi-plus-circle-fill me-2 fs-5"></i>
                                    <span>Nuevo Apoyo</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjetas KPI de Resumen --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-primary">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Convenios Totales</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['total'] ?? 0) }}</h3>
                            <small class="text-primary fw-semibold"><i class="bi bi-check-circle me-1"></i>{{ number_format($stats['activos'] ?? 0) }} activos</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-info">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-box-seam fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Comidas Habilitadas</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['habilitadas'] ?? 0) }}</h3>
                            <small class="text-info fw-semibold">Cupos autorizados</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-warning">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-check2-all fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Comidas Consumidas</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['consumidas'] ?? 0) }}</h3>
                            <small class="text-warning fw-semibold">Servidas en comedor</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-success">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-ticket-perforated fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Disponibles Restantes</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['disponibles'] ?? 0) }}</h3>
                            <small class="text-success fw-semibold">Cupos por entregar</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta de Filtros de Búsqueda --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-3 p-md-4">
                        <form action="{{ route('admin.apoyos.index') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-7">
                                <label for="filtro" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-search text-primary me-1"></i> Búsqueda Rápida
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-building text-secondary"></i>
                                    </span>
                                    <input type="text" class="form-control border-secondary-subtle" name="filtro" id="filtro"
                                        placeholder="Buscar por institución o código (ej: APOYO-XXXX)..."
                                        value="{{ $request->filtro ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="estatus" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-toggle-on text-primary me-1"></i> Estatus
                                </label>
                                <select name="estatus" id="estatus" class="form-select border-secondary-subtle">
                                    <option value="TODOS">Todos los convenios</option>
                                    <option value="1" {{ $request->estatus === '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ $request->estatus === '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center shadow-sm" type="submit" title="Filtrar">
                                    <i class="bi bi-search me-1"></i> Filtrar
                                </button>
                                @if($request->filled('filtro') || ($request->filled('estatus') && $request->estatus !== 'TODOS'))
                                    <a href="{{ route('admin.apoyos.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="Limpiar Filtros">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Convenios de Apoyo --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ $apoyos->total() }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Convenios Registrados</h5>
                            @if($request->filled('filtro') || ($request->filled('estatus') && $request->estatus !== 'TODOS'))
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-2">
                                    <i class="bi bi-funnel-fill me-1"></i> Filtro activo
                                </span>
                            @endif
                        </div>
                        <div>
                            @if($request->filled('filtro') || ($request->filled('estatus') && $request->estatus !== 'TODOS'))
                                <a href="{{ route('admin.apoyos.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-arrow-repeat me-1"></i> Ver todos
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Institución / Beneficiario</th>
                                    <th scope="col">Código QR</th>
                                    <th scope="col" class="text-center">Habilitadas</th>
                                    <th scope="col" class="text-center">Consumidas</th>
                                    <th scope="col" class="text-center" style="min-width: 150px;">Disponibles</th>
                                    <th scope="col" class="text-center">Estatus</th>
                                    <th scope="col" class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($apoyos as $apoyo)
                                    @php
                                        $disponibles = max(0, $apoyo->cantidad_habilitada - $apoyo->cantidad_consumida);
                                        $porcentajeConsumido = $apoyo->cantidad_habilitada > 0 
                                            ? min(100, round(($apoyo->cantidad_consumida / $apoyo->cantidad_habilitada) * 100)) 
                                            : 0;
                                    @endphp
                                    <tr>
                                        {{-- Institución con Avatar --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                     style="width: 40px; height: 40px; background-color: #0d6efd; font-size: 15px;">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">
                                                        {{ $apoyo->institucion }}
                                                    </div>
                                                    <small class="text-muted">
                                                        Convenio #{{ $apoyo->id }} &bull; Registrado {{ $apoyo->created_at ? $apoyo->created_at->format('d/m/Y') : '' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Código QR con botón para copiar --}}
                                        <td>
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <span class="badge bg-secondary-subtle text-primary font-monospace px-2 py-1 fs-7 border">
                                                    {{ $apoyo->codigo }}
                                                </span>
                                                <button type="button" class="btn btn-sm btn-light p-1 border-0 rounded" 
                                                        onclick="copyToClipboard('{{ $apoyo->codigo }}', this)" 
                                                        title="Copiar código">
                                                    <i class="bi bi-clipboard text-secondary small"></i>
                                                </button>
                                            </div>
                                        </td>

                                        {{-- Habilitadas --}}
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-primary border border-info-subtle px-2 py-1 fw-bold">
                                                {{ number_format($apoyo->cantidad_habilitada) }}
                                            </span>
                                        </td>

                                        {{-- Consumidas --}}
                                        <td class="text-center">
                                            <span class="badge bg-warning-subtle text-primary border border-warning-subtle px-2 py-1 fw-bold">
                                                {{ number_format($apoyo->cantidad_consumida) }}
                                            </span>
                                        </td>

                                        {{-- Disponibles con Barra de Progreso --}}
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge {{ $disponibles > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-3 py-1 fw-bold mb-1">
                                                    {{ number_format($disponibles) }}
                                                </span>
                                                <div class="progress w-100" style="height: 5px; max-width: 100px;" title="{{ $porcentajeConsumido }}% consumido">
                                                    <div class="progress-bar {{ $porcentajeConsumido >= 100 ? 'bg-danger' : ($porcentajeConsumido > 75 ? 'bg-warning' : 'bg-primary') }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $porcentajeConsumido }}%;" 
                                                         aria-valuenow="{{ $porcentajeConsumido }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Estatus --}}
                                        <td class="text-center">
                                            @if ($apoyo->estatus)
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
                                                {{-- Imprimir tickets --}}
                                                <a href="{{ route('admin.apoyos.ticket', $apoyo->id) }}" target="_blank"
                                                   class="btn btn-sm btn-outline-primary rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;" 
                                                   title="Imprimir Tickets con QR">
                                                    <i class="bi bi-printer"></i>
                                                </a>

                                                {{-- Editar --}}
                                                <a href="{{ route('admin.apoyos.edit', $apoyo->id) }}" 
                                                   class="btn btn-sm btn-outline-warning rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                   style="width: 32px; height: 32px;" 
                                                   title="Editar Convenio">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                {{-- Eliminar con Modal --}}
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle shadow-sm p-0 d-inline-flex align-items-center justify-content-center" 
                                                        style="width: 32px; height: 32px;" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEliminarApoyo{{ $apoyo->id }}" 
                                                        title="Eliminar Convenio">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            {{-- Modal de Confirmación de Eliminación --}}
                                            <div class="modal fade" id="modalEliminarApoyo{{ $apoyo->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4 text-start">
                                                        <div class="modal-header bg-danger text-white py-3">
                                                            <h5 class="modal-title fw-bold">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Apoyo
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            ¿Está seguro de eliminar el convenio de apoyo de 
                                                            <strong class="text-dark">{{ $apoyo->institucion }}</strong> con código 
                                                            <span class="badge bg-secondary-subtle text-primary font-monospace">{{ $apoyo->codigo }}</span>?
                                                            <p class="small text-danger mt-2 mb-0">
                                                                <i class="bi bi-info-circle me-1"></i> Esta acción no se puede deshacer y deshabilitará los tickets asociados.
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('admin.apoyos.destroy', $apoyo->id) }}" method="POST" class="d-inline-block m-0">
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
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-ticket-detailed text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No se encontraron apoyos institucionales</h5>
                                                <p class="text-muted small mb-3">Intenta cambiar los parámetros del filtro o registra un nuevo convenio.</p>
                                                <a href="{{ route('admin.apoyos.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Limpiar filtros
                                                </a>
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
                                Mostrando <strong>{{ $apoyos->firstItem() ?? 0 }}</strong> a <strong>{{ $apoyos->lastItem() ?? 0 }}</strong> de <strong>{{ $apoyos->total() }}</strong> convenios
                            </div>
                            <div>
                                {{ $apoyos->appends($request->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function copyToClipboard(text, btnElement) {
        navigator.clipboard.writeText(text).then(function() {
            const originalIcon = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="bi bi-check text-success small"></i>';
            setTimeout(function() {
                btnElement.innerHTML = originalIcon;
            }, 1500);
        }).catch(function(err) {
            console.error('Error al copiar:', err);
        });
    }
    </script>
@endsection