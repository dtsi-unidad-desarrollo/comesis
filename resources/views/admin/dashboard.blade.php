@extends('layouts.app')

@section('title', 'Cpanel - Dashboard Administrativo')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>

    <section class="section dashboard mt-2">
        <!-- Banner de Bienvenida y Estado del Servicio -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #0d274d 0%, #1e4a84 100%); border-radius: 12px;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-speedometer2 fs-3 text-warning"></i>
                                    <h4 class="mb-0 fw-bold text-white">Panel de Control y Monitoreo (Cpanel)</h4>
                                </div>
                                <p class="mb-2 text-white-50 small">
                                    <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                    &nbsp;|&nbsp;
                                    <i class="bi bi-person-badge me-1"></i> Sesión activa: <strong>{{ Auth::user()->nombre ?? 'Administrador' }}</strong>
                                </p>
                                <div>
                                    @if ($servicioActual)
                                        <span class="badge bg-success bg-gradient px-3 py-2 fs-7 shadow-sm">
                                            <i class="bi bi-check-circle-fill me-1"></i> Servicio en curso: <strong>{{ $servicioActual->nombre }}</strong>
                                            ({{ \Carbon\Carbon::parse($servicioActual->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($servicioActual->hora_cierre)->format('h:i A') }})
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-gradient px-3 py-2 fs-7">
                                            <i class="bi bi-moon-stars me-1"></i> Fuera de horario de servicio activo
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.recepcion.index') }}" class="btn btn-warning fw-bold px-3 shadow-sm text-dark">
                                    <i class="bi bi-upc-scan me-1"></i> Recepción / Turno
                                </a>
                                <a href="{{ route('admin.apoyos.index') }}" class="btn btn-outline-light px-3">
                                    <i class="bi bi-building-check me-1"></i> Apoyos
                                </a>
                                <a href="{{ route('admin.comensales.index') }}" class="btn btn-outline-light px-3">
                                    <i class="bi bi-people me-1"></i> Comensales
                                </a>
                                <a href="{{ route('admin.entradas.index') }}" class="btn btn-outline-light px-3">
                                    <i class="bi bi-file-earmark-bar-graph me-1"></i> Reportes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Métricas Principales (KPIs) -->
        <div class="row g-3 mb-4">
            <!-- Card 1: Bandejas Servidas Hoy -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card sales-card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small text-uppercase fw-bold">Bandejas Servidas Hoy</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold">Hoy</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary me-3" style="width: 54px; height: 54px; font-size: 26px;">
                                <i class="bi bi-cup-hot-fill"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-dark" id="daily-total">{{ $stats['hoy']['total'] }}</h3>
                                <small class="text-muted">bandejas entregadas</small>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i> Est: <strong>{{ $stats['hoy']['ESTUDIANTE'] }}</strong></span>
                            <span><i class="bi bi-circle-fill text-success" style="font-size: 8px;"></i> Emp: <strong>{{ $stats['hoy']['EMPLEADO'] }}</strong></span>
                            <span><i class="bi bi-circle-fill text-purple" style="font-size: 8px; color: #6f42c1;"></i> Apo: <strong>{{ $stats['hoy']['APOYO'] }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Disponibilidad y Capacidad -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card revenue-card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small text-uppercase fw-bold">Capacidad & Cupos</span>
                            <span class="badge bg-success bg-opacity-10 text-success fw-semibold">{{ $stats['hoy']['servicio_nombre'] }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success me-3" style="width: 54px; height: 54px; font-size: 26px;">
                                <i class="bi bi-pie-chart-fill"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['hoy']['no_entregadas'], 0, ',', '.') }}</h3>
                                <small class="text-muted">disponibles de {{ number_format($stats['hoy']['disponibles'], 0, ',', '.') }}</small>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Ocupación de servicio</span>
                                <span class="fw-bold text-dark">{{ $stats['hoy']['porcentaje'] }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $stats['hoy']['porcentaje']) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Padrón de Comensales -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card customers-card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small text-uppercase fw-bold">Padrón de Comensales</span>
                            <a href="{{ route('admin.comensales.index') }}" class="badge bg-info bg-opacity-10 text-info text-decoration-none fw-semibold">Ver todos</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info me-3" style="width: 54px; height: 54px; font-size: 26px;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['globales']['total_comensales'], 0, ',', '.') }}</h3>
                                <small class="text-muted">comensales activos</small>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-mortarboard me-1 text-primary"></i> Estudiantes: <strong>{{ $stats['globales']['estudiantes_activos'] }}</strong></span>
                            <span><i class="bi bi-briefcase me-1 text-success"></i> Empleados: <strong>{{ $stats['globales']['empleados_activos'] }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Apoyos Institucionales -->
            <div class="col-xxl-3 col-md-6">
                <div class="card info-card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small text-uppercase fw-bold">Apoyos Institucionales</span>
                            <a href="{{ route('admin.apoyos.index') }}" class="badge bg-warning bg-opacity-10 text-dark text-decoration-none fw-semibold">Módulo</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning me-3" style="width: 54px; height: 54px; font-size: 26px;">
                                <i class="bi bi-qr-code-scan"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-dark">{{ $stats['globales']['apoyos_activos'] }}</h3>
                                <small class="text-muted">convenios vigentes</small>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Raciones consumidas</span>
                                <span class="fw-bold text-dark">{{ $stats['globales']['apoyos_consumidas'] }} / {{ $stats['globales']['apoyos_habilitadas'] }}</span>
                            </div>
                            @php
                                $pctApoyos = $stats['globales']['apoyos_habilitadas'] > 0 
                                    ? round(($stats['globales']['apoyos_consumidas'] / $stats['globales']['apoyos_habilitadas']) * 100, 1) 
                                    : 0;
                            @endphp
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, $pctApoyos) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Gráficos Analíticos -->
        <div class="row g-3 mb-4">
            <!-- Gráfico 1: Distribución de Hoy (Doughnut) -->
            <div class="col-lg-5 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-pie-chart text-primary me-1"></i> Distribución de Hoy
                        </h6>
                        <small class="text-muted">{{ Carbon\Carbon::now()->format('d/m/Y') }}</small>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="chart-container my-auto position-relative" style="height: 230px;">
                            <canvas id="todayChart"></canvas>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <div class="row g-2 text-center small">
                                <div class="col-3">
                                    <div class="p-2 rounded bg-light border">
                                        <div class="text-primary fw-bold fs-6">{{ $stats['hoy']['ESTUDIANTE'] }}</div>
                                        <div class="text-muted" style="font-size: 11px;">Estudiantes</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="p-2 rounded bg-light border">
                                        <div class="text-success fw-bold fs-6">{{ $stats['hoy']['EMPLEADO'] }}</div>
                                        <div class="text-muted" style="font-size: 11px;">Empleados</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="p-2 rounded bg-light border">
                                        <div class="fw-bold fs-6" style="color: #6f42c1;">{{ $stats['hoy']['APOYO'] }}</div>
                                        <div class="text-muted" style="font-size: 11px;">Apoyos</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="p-2 rounded bg-light border">
                                        <div class="text-secondary fw-bold fs-6">{{ $stats['hoy']['no_entregadas'] }}</div>
                                        <div class="text-muted" style="font-size: 11px;">Restantes</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico 2: Evolución Semanal de Entregas (Barras Agrupadas) -->
            <div class="col-lg-7 mb-3">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-bar-chart-line text-success me-1"></i> Bandejas Entregadas esta Semana
                            </h6>
                            <small class="text-muted">Semana actual (Lunes a Domingo)</small>
                        </div>
                        <span class="badge bg-primary px-2 py-1">Total: {{ $stats['semanal']['total']['TODOS'] }} bandejas</span>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="chart-container position-relative" style="height: 230px;">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                        <div class="mt-3 pt-3 border-top d-flex flex-wrap justify-content-around text-center small text-muted">
                            <div>
                                <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background-color: #0d6efd;"></span>
                                Estudiantes: <strong class="text-dark">{{ $stats['semanal']['total']['ESTUDIANTE'] }}</strong>
                            </div>
                            <div>
                                <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background-color: #198754;"></span>
                                Empleados: <strong class="text-dark">{{ $stats['semanal']['total']['EMPLEADO'] }}</strong>
                            </div>
                            <div>
                                <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background-color: #6f42c1;"></span>
                                Apoyos: <strong class="text-dark">{{ $stats['semanal']['total']['APOYO'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico 3: Tendencia de Consumo Mensual (Área Suave) -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-graph-up-arrow text-info me-1"></i> Tendencia de Consumo Diario en el Mes
                            </h6>
                            <small class="text-muted">{{ ucfirst($stats['mensual']['mes_nombre']) }} {{ $stats['mensual']['anio'] }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border">
                                Total Mes: <strong class="text-primary">{{ $stats['mensual']['total_mes'] }}</strong>
                            </span>
                            <span class="badge bg-light text-dark border">
                                Días con Servicio: <strong>{{ $stats['mensual']['dias_activos'] }}</strong>
                            </span>
                            <span class="badge bg-light text-dark border">
                                Promedio: <strong>{{ $stats['mensual']['promedio_diario'] }} / día</strong>
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart-container position-relative" style="height: 240px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Actividad Reciente y Monitoreo de Hardware/Terminales -->
        <div class="row g-3">
            <!-- Tabla de Últimas Entradas Registradas -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-clock-history text-danger me-1"></i> Últimas Entradas Registradas (En Vivo)
                        </h6>
                        <a href="{{ route('admin.entradas.index') }}" class="btn btn-sm btn-outline-primary">
                            Ver reporte completo <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th class="ps-3">Hora</th>
                                        <th>Comensal / Beneficiario</th>
                                        <th>Tipo</th>
                                        <th>Servicio</th>
                                        <th>Cajero / ATM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ultimasEntradas as $entrada)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="badge bg-light text-dark border font-monospace">{{ $entrada->hora }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $entrada->nombres }} {{ $entrada->apellidos }}</div>
                                                <small class="text-muted font-monospace">{{ $entrada->cedula }}</small>
                                            </td>
                                            <td>
                                                @if ($entrada->tipo_comensal === 'ESTUDIANTE')
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                                        <i class="bi bi-mortarboard me-1"></i> ESTUDIANTE
                                                    </span>
                                                @elseif ($entrada->tipo_comensal === 'EMPLEADO')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                                        <i class="bi bi-briefcase me-1"></i> EMPLEADO
                                                    </span>
                                                @elseif ($entrada->tipo_comensal === 'APOYO')
                                                    <span class="badge bg-purple bg-opacity-10 border px-2 py-1" style="background-color: #f3e8ff; color: #6f42c1; border-color: #d8b4fe !important;">
                                                        <i class="bi bi-building-check me-1"></i> APOYO INST.
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">
                                                        {{ $entrada->tipo_comensal }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $entrada->comida }}</span>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-dark">
                                                    {{ $entrada->atm->nombre ?? 'Terminal General' }}
                                                </div>
                                                <small class="text-muted" style="font-size: 11px;">
                                                    {{ $entrada->allowedBy->nombre ?? 'Sistema' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-2 d-block mb-1 text-secondary"></i>
                                                No hay registros de entradas registradas el día de hoy.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Estado de ATMs y Acciones Rápidas -->
            <div class="col-lg-4 mb-4">
                <div class="d-flex flex-column gap-3">
                    <!-- Estado de ATMs / Torniquetes -->
                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-hdd-network text-primary me-1"></i> Terminales & Torniquetes
                            </h6>
                            <a href="{{ route('admin.atms.index') }}" class="small text-decoration-none">Gestionar</a>
                        </div>
                        <div class="card-body p-3">
                            <div class="list-group list-group-flush">
                                @forelse ($atms as $atm)
                                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="badge rounded-circle p-1 me-2 {{ $atm->en_uso ? 'bg-success' : 'bg-secondary' }}" style="width: 10px; height: 10px;"></span>
                                            <div>
                                                <div class="fw-bold small text-dark">{{ $atm->nombre }}</div>
                                                <small class="text-muted" style="font-size: 11px;">
                                                    Torniquete: {{ $atm->torniquete->nombre ?? 'Asignado' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div>
                                            @if ($atm->en_uso)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 small">
                                                    <i class="bi bi-person-fill-check"></i> En uso
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border small">
                                                    Disponible
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-2 text-muted small">
                                        No hay ATMs registrados.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Accesos Rápidos de Administración -->
                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-3 pb-2">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-lightning-charge text-warning me-1"></i> Acciones del Sistema
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.apoyos.create') }}" class="btn btn-light text-start border shadow-none d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-plus-circle text-success me-2"></i> Crear Nuevo Apoyo</span>
                                    <i class="bi bi-chevron-right text-muted small"></i>
                                </a>
                                <a href="{{ route('admin.comensales.create') }}" class="btn btn-light text-start border shadow-none d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-person-plus text-primary me-2"></i> Registrar Comensal</span>
                                    <i class="bi bi-chevron-right text-muted small"></i>
                                </a>
                                <a href="{{ route('admin.sincronizar.data.index') }}" class="btn btn-light text-start border shadow-none d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-arrow-repeat text-info me-2"></i> Sincronizar Bases de Datos</span>
                                    <i class="bi bi-chevron-right text-muted small"></i>
                                </a>
                                <a href="{{ route('admin.comensales.export') }}" class="btn btn-light text-start border shadow-none d-flex align-items-center justify-content-between">
                                    <span><i class="bi bi-file-earmark-excel text-success me-2"></i> Exportar Comensales (Excel)</span>
                                    <i class="bi bi-download text-muted small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Gráfico de Hoy (Doughnut)
            const ctxToday = document.getElementById('todayChart');
            if (ctxToday) {
                new Chart(ctxToday, {
                    type: 'doughnut',
                    data: {
                        labels: ['Estudiantes', 'Empleados', 'Apoyos Institucionales', 'Bandejas Restantes'],
                        datasets: [{
                            data: [
                                {{ $stats['hoy']['ESTUDIANTE'] }},
                                {{ $stats['hoy']['EMPLEADO'] }},
                                {{ $stats['hoy']['APOYO'] }},
                                {{ $stats['hoy']['no_entregadas'] }}
                            ],
                            backgroundColor: [
                                '#0d6efd',
                                '#198754',
                                '#6f42c1',
                                '#e9ecef'
                            ],
                            borderColor: [
                                '#0d6efd',
                                '#198754',
                                '#6f42c1',
                                '#dee2e6'
                            ],
                            borderWidth: 1,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 11 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        let total = {{ $stats['hoy']['disponibles'] > 0 ? $stats['hoy']['disponibles'] : 1 }};
                                        let percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Gráfico Semanal (Barras Agrupadas)
            const ctxWeek = document.getElementById('weeklyChart');
            if (ctxWeek) {
                new Chart(ctxWeek, {
                    type: 'bar',
                    data: {
                        labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
                        datasets: [
                            {
                                label: 'Estudiantes',
                                data: [
                                    {{ $stats['semanal']['lunes']['ESTUDIANTE'] }},
                                    {{ $stats['semanal']['martes']['ESTUDIANTE'] }},
                                    {{ $stats['semanal']['miercoles']['ESTUDIANTE'] }},
                                    {{ $stats['semanal']['jueves']['ESTUDIANTE'] }},
                                    {{ $stats['semanal']['viernes']['ESTUDIANTE'] }},
                                    {{ $stats['semanal']['sabado']['ESTUDIANTE'] }},
                                    {{ $stats['semanal']['domingo']['ESTUDIANTE'] }}
                                ],
                                backgroundColor: 'rgba(13, 110, 253, 0.85)',
                                borderColor: '#0d6efd',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Empleados',
                                data: [
                                    {{ $stats['semanal']['lunes']['EMPLEADO'] }},
                                    {{ $stats['semanal']['martes']['EMPLEADO'] }},
                                    {{ $stats['semanal']['miercoles']['EMPLEADO'] }},
                                    {{ $stats['semanal']['jueves']['EMPLEADO'] }},
                                    {{ $stats['semanal']['viernes']['EMPLEADO'] }},
                                    {{ $stats['semanal']['sabado']['EMPLEADO'] }},
                                    {{ $stats['semanal']['domingo']['EMPLEADO'] }}
                                ],
                                backgroundColor: 'rgba(25, 135, 84, 0.85)',
                                borderColor: '#198754',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Apoyos',
                                data: [
                                    {{ $stats['semanal']['lunes']['APOYO'] }},
                                    {{ $stats['semanal']['martes']['APOYO'] }},
                                    {{ $stats['semanal']['miercoles']['APOYO'] }},
                                    {{ $stats['semanal']['jueves']['APOYO'] }},
                                    {{ $stats['semanal']['viernes']['APOYO'] }},
                                    {{ $stats['semanal']['sabado']['APOYO'] }},
                                    {{ $stats['semanal']['domingo']['APOYO'] }}
                                ],
                                backgroundColor: 'rgba(111, 66, 193, 0.85)',
                                borderColor: '#6f42c1',
                                borderWidth: 1,
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                                ticks: { precision: 0 }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: { boxWidth: 12, font: { size: 11 } }
                            }
                        }
                    }
                });
            }

            // 3. Gráfico Mensual (Línea con Área Suave)
            const ctxMonth = document.getElementById('monthlyChart');
            if (ctxMonth) {
                const monthLabels = {!! json_encode(array_column($stats['mensual']['dias'], 'dia')) !!};
                const monthTotals = {!! json_encode(array_column($stats['mensual']['dias'], 'total')) !!};
                const monthEstudiantes = {!! json_encode(array_column($stats['mensual']['dias'], 'ESTUDIANTE')) !!};
                const monthEmpleados = {!! json_encode(array_column($stats['mensual']['dias'], 'EMPLEADO')) !!};
                const monthApoyos = {!! json_encode(array_column($stats['mensual']['dias'], 'APOYO')) !!};

                new Chart(ctxMonth, {
                    type: 'line',
                    data: {
                        labels: monthLabels,
                        datasets: [
                            {
                                label: 'Total Diario',
                                data: monthTotals,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                fill: true,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                borderWidth: 2
                            },
                            {
                                label: 'Estudiantes',
                                data: monthEstudiantes,
                                borderColor: '#0dcaf0',
                                borderDash: [4, 4],
                                fill: false,
                                tension: 0.35,
                                pointRadius: 2,
                                borderWidth: 1.5
                            },
                            {
                                label: 'Empleados',
                                data: monthEmpleados,
                                borderColor: '#198754',
                                borderDash: [4, 4],
                                fill: false,
                                tension: 0.35,
                                pointRadius: 2,
                                borderWidth: 1.5
                            },
                            {
                                label: 'Apoyos',
                                data: monthApoyos,
                                borderColor: '#6f42c1',
                                borderDash: [4, 4],
                                fill: false,
                                tension: 0.35,
                                pointRadius: 2,
                                borderWidth: 1.5
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                                ticks: { precision: 0 }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: { boxWidth: 12, font: { size: 11 } }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
