@extends('layouts.app')

@section('title', 'Historial de Entradas')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif

    <div id="alert"></div>

    {{-- Respuesta de validaciones --}}
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-start shadow-sm mb-4" role="alert">
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
        {{-- Encabezado Principal con Banner --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-clock-history me-1"></i> Control de Accesos
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Historial de Entradas al Comedor</h2>
                                <p class="mb-0 text-white-50">Registro en tiempo real de comensales, bandejas servidas y consumo diario</p>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @include('admin.entradas.partials.modalFormularioReporte')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjetas de Estadísticas / KPIs Rápidos de Hoy --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-primary">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-card-checklist fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Entradas Hoy</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($statsHoy['total'] ?? 0) }}</h3>
                            <small class="text-primary fw-semibold"><i class="bi bi-calendar-check me-1"></i>Total servidas</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-info">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-mortarboard fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Estudiantes Hoy</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($statsHoy['estudiantes'] ?? 0) }}</h3>
                            <small class="text-info fw-semibold">Regulares y foráneos</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-success">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-briefcase fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Empleados Hoy</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($statsHoy['empleados'] ?? 0) }}</h3>
                            <small class="text-success fw-semibold">Personal UNELLEZ</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-warning">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Apoyos Hoy</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($statsHoy['apoyos'] ?? 0) }}</h3>
                            <small class="text-warning fw-semibold">Convenios institucionales</small>
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
                        <form action="{{ route('admin.entradas.index') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-2 col-lg-2">
                                <label for="servicio" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-cup-hot text-primary me-1"></i> Servicio
                                </label>
                                <select name="servicio" id="servicio" class="form-select border-secondary-subtle">
                                    <option value="0">Todos los servicios</option>
                                    @foreach ($servicios as $servicio)
                                        <option value="{{ $servicio->nombre }}" {{ $request->servicio == $servicio->nombre ? 'selected' : '' }}>
                                            {{ $servicio->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-lg-2">
                                <label for="fecha" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-calendar-event text-primary me-1"></i> Fecha
                                </label>
                                <input type="date" class="form-control border-secondary-subtle" name="fecha" id="fecha"
                                    value="{{ $request->fecha ? \Carbon\Carbon::parse($request->fecha)->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-3 col-lg-3">
                                <label for="tipo" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-person-badge text-primary me-1"></i> Tipo de Comensal
                                </label>
                                <select name="tipo" id="tipo" class="form-select border-secondary-subtle">
                                    <option value="0">Todos los tipos</option>
                                    @foreach ($tipos as $tipoItem)
                                        <option value="{{ $tipoItem }}" {{ (string)$request->tipo === (string)$tipoItem ? 'selected' : '' }}>
                                            {{ $tipoItem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 col-lg-3">
                                <label for="filtro" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-search text-primary me-1"></i> Búsqueda Rápida
                                </label>
                                <input type="text" class="form-control border-secondary-subtle" name="filtro" id="filtro"
                                    placeholder="Cédula, nombre, subtipo..." 
                                    value="{{ $request->filtro ?? '' }}">
                            </div>

                            <input type="hidden" name="activo" value="true">

                            <div class="col-md-2 col-lg-2 d-flex gap-2">
                                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center shadow-sm" type="submit" title="Buscar">
                                    <i class="bi bi-search me-1"></i> Filtrar
                                </button>
                                @if($request->activo || $request->filled('tipo') || $request->filled('filtro') || $request->filled('fecha') || ($request->filled('servicio') && $request->servicio !== '0'))
                                    <a href="{{ route('admin.entradas.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="Limpiar Filtros">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Resultados --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                {{ $entradas->total() }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Registros de Entradas</h5>
                            @if($request->activo || $request->filled('tipo') || $request->filled('filtro') || $request->filled('fecha') || ($request->filled('servicio') && $request->servicio !== '0'))
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-2">
                                    <i class="bi bi-funnel-fill me-1"></i> Filtro activo
                                    @if($request->filled('tipo') && $request->tipo !== '0' && $request->tipo !== 'TODOS')
                                        ({{ $request->tipo }})
                                    @endif
                                </span>
                            @endif
                        </div>
                        <div>
                            @if($request->activo || $request->filled('tipo') || $request->filled('filtro') || $request->filled('fecha') || ($request->filled('servicio') && $request->servicio !== '0'))
                                <a href="{{ route('admin.entradas.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-arrow-repeat me-1"></i> Restablecer vista
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .5px;">
                                <tr>
                                    <th scope="col" class="ps-4">Comensal</th>
                                    <th scope="col">Cédula</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Subtipo</th>
                                    <th scope="col">Comida</th>
                                    <th scope="col">Fecha / Hora</th>
                                    <th scope="col">Taquilla (ATM)</th>
                                    <th scope="col">Taquillero</th>
                                    <th scope="col" class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entradas as $entrada)
                                    <tr>
                                        {{-- Comensal con Iniciales --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $inicial = strtoupper(substr(trim($entrada->nombres ?? 'U'), 0, 1));
                                                    $colorBg = match(strtoupper($entrada->tipo_comensal ?? '')) {
                                                        'ESTUDIANTE', 'ESTUDIANTE FORANEO' => '#0d6efd',
                                                        'EMPLEADO' => '#198754',
                                                        'APOYO' => '#f59e0b',
                                                        default => '#6c757d'
                                                    };
                                                @endphp
                                                <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                     style="width: 38px; height: 38px; background-color: {{ $colorBg }}; font-size: 14px;">
                                                    {{ $inicial }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">
                                                        {{ $entrada->nombres }} {{ $entrada->apellidos }}
                                                    </div>
                                                    @if($entrada->carrera)
                                                        <small class="text-muted d-block text-truncate" style="max-width: 220px;" title="{{ $entrada->carrera }}">
                                                            <i class="bi bi-journal-bookmark me-1"></i>{{ $entrada->carrera }}
                                                        </small>
                                                    @elseif($entrada->sede)
                                                        <small class="text-muted d-block">
                                                            <i class="bi bi-geo-alt me-1"></i>{{ $entrada->sede }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Cédula --}}
                                        <td>
                                            <span class="badge bg-secondary-subtle text-primary font-monospace px-2 py-1 fs-7 border">
                                                {{ $entrada->nacionalidad ? $entrada->nacionalidad . '-' : '' }}{{ $entrada->cedula }}
                                            </span>
                                        </td>

                                        {{-- Tipo de Comensal con Badges Semánticos --}}
                                        <td>
                                            @php
                                                $tipoUpper = strtoupper($entrada->tipo_comensal ?? '');
                                            @endphp
                                            @if($tipoUpper === 'ESTUDIANTE' || $tipoUpper === 'ESTUDIANTE FORANEO')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    <i class="bi bi-mortarboard-fill me-1"></i> {{ $entrada->tipo_comensal }}
                                                </span>
                                            @elseif($tipoUpper === 'EMPLEADO')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    <i class="bi bi-briefcase-fill me-1"></i> EMPLEADO
                                                </span>
                                            @elseif($tipoUpper === 'APOYO')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                                    <i class="bi bi-building me-1"></i> APOYO
                                                </span>
                                            @else
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                                    <i class="bi bi-person-fill me-1"></i> {{ $entrada->tipo_comensal ?? 'OTRO' }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Subtipo Registrado --}}
                                        <td>
                                            @php
                                                $subtipoVal = $entrada->sub_tipo_display;
                                            @endphp
                                            @if(!empty($subtipoVal))
                                                <span class="badge bg-light text-dark border px-2 py-1 fw-semibold" title="{{ $subtipoVal }}">
                                                    <i class="bi bi-tag-fill text-primary me-1"></i>{{ $subtipoVal }}
                                                </span>
                                            @elseif(strtoupper($entrada->tipo_comensal ?? '') === 'APOYO')
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 fw-semibold">
                                                    <i class="bi bi-building me-1"></i>CONVENIO
                                                </span>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="bi bi-dash text-secondary"></i> Sin subtipo
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Comida / Servicio --}}
                                        <td>
                                            @if(strtoupper($entrada->comida) === 'ALMUERZO')
                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                    <i class="bi bi-sun-fill text-warning me-1"></i> ALMUERZO
                                                </span>
                                            @elseif(strtoupper($entrada->comida) === 'CENA')
                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                    <i class="bi bi-moon-stars-fill text-primary me-1"></i> CENA
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark border px-2 py-1">
                                                    <i class="bi bi-cup-hot text-secondary me-1"></i> {{ $entrada->comida }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Fecha y Hora --}}
                                        <td>
                                            <div class="fw-semibold text-dark">
                                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($entrada->fecha)->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock text-muted me-1"></i>{{ $entrada->hora }}
                                            </small>
                                        </td>

                                        {{-- Taquilla --}}
                                        <td>
                                            <span class="text-secondary small fw-semibold">
                                                <i class="bi bi-display text-primary me-1"></i>
                                                {{ $entrada->atm->nombre ?? 'N/A' }}
                                            </span>
                                        </td>

                                        {{-- Taquillero --}}
                                        <td>
                                            <span class="text-secondary small">
                                                <i class="bi bi-person-check text-success me-1"></i>
                                                {{ $entrada->user->nombre ?? 'N/A' }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-1 align-items-center">
                                                @include('admin.entradas.partials.modaldialog')

                                                @if (Auth::user()->rol <= 2)
                                                    @include('admin.entradas.partials.modal')
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                                <h6 class="fw-bold mb-1">No se encontraron entradas registradas</h6>
                                                <p class="small mb-3">Intenta cambiar los filtros de tipo, fecha o búsqueda.</p>
                                                @if($request->activo || $request->filled('tipo') || $request->filled('filtro') || $request->filled('fecha') || ($request->filled('servicio') && $request->servicio !== '0'))
                                                    <a href="{{ route('admin.entradas.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                                        <i class="bi bi-arrow-repeat me-1"></i> Ver todas las entradas
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación y Resumen --}}
                    @if($entradas->hasPages() || $entradas->total() > 0)
                        <div class="card-footer bg-white py-3 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <small class="text-muted">
                                Mostrando del <strong>{{ $entradas->firstItem() ?? 0 }}</strong> al <strong>{{ $entradas->lastItem() ?? 0 }}</strong> de <strong>{{ number_format($entradas->total()) }}</strong> entradas registradas
                            </small>
                            <div>
                                {{ $entradas->appends([
                                    'filtro'   => $request->filtro, 
                                    'servicio' => $request->servicio, 
                                    'tipo'     => $request->tipo,
                                    'fecha'    => $request->fecha, 
                                    'activo'   => $request->activo
                                ])->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </section>
@endsection
