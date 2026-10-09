@extends('layouts.app')

@section('title', 'Sincronización de Datos - Dux y Terepaima')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>

    <section class="section">
        <!-- Banner Institucional Superior -->
        <div class="card border-0 shadow-sm text-white mb-4 rounded-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%);">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-20 text-white shadow-sm flex-shrink-0" style="width: 54px; height: 54px; font-size: 26px;">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h4 class="mb-0 fw-bold text-white">Sincronización de Datos</h4>
                                <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-2 py-1 small">Sistemas Centrales</span>
                            </div>
                            <p class="mb-0 text-white-50 small">
                                Actualice el padrón local de comensales desde los sistemas centrales universitarios: <strong>DUX</strong> (Estudiantes) y <strong>TEREPAIMA</strong> (Empleados/Personal).
                            </p>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('admin.sincronizarData') }}" method="POST" onsubmit="return confirm('¿Desea iniciar la sincronización completa de Estudiantes y Empleados? Este proceso actualizará la base de datos local.');">
                            @csrf
                            <input type="hidden" name="tipo" value="todos">
                            <button type="submit" class="btn btn-warning fw-bold px-4 py-2 rounded-pill shadow-sm text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-arrow-down-fill"></i>
                                <span>Sincronizar Todo Ahora</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- 1. Sistema DUX (Estudiantes) -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary shadow-sm" style="width: 48px; height: 48px; font-size: 22px;">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Sistema DUX (SISE / ARSE)</h5>
                                <small class="text-muted">Padrón de Estudiantes Universitarios</small>
                            </div>
                        </div>
                        <div>
                            @if ($duxStatus['connected'])
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Conectado
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1.5 fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Desconectado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="p-3 rounded-4 mb-3 border bg-light">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted d-block mb-1">Servidor / Host:</span>
                                    <div class="fw-bold font-monospace text-dark">{{ $duxStatus['host'] ?? '127.0.0.1' }}</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block mb-1">Base de Datos:</span>
                                    <div class="fw-bold font-monospace text-dark">{{ $duxStatus['database'] ?? 'N/D' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 text-center mb-4">
                            <div class="col-6">
                                <div class="p-3 border rounded-4 bg-white shadow-sm h-100">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Estudiantes en DUX</span>
                                    <h3 class="mb-1 fw-bold text-primary">{{ number_format($duxStatus['activos_remoto'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">de {{ number_format($duxStatus['total_remoto'], 0, ',', '.') }} totales</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded-4 bg-white shadow-sm h-100">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Registrados en COMESIS</span>
                                    <h3 class="mb-1 fw-bold text-success">{{ number_format($localStats['estudiantes_activos'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">locales activos</small>
                                </div>
                            </div>
                        </div>

                        @if (session('sync_estudiantes_result'))
                            @php $resEst = session('sync_estudiantes_result'); @endphp
                            <div class="alert alert-{{ $resEst['success'] ? 'success' : 'danger' }} rounded-3 py-2 px-3 small mb-3 border-0">
                                <i class="bi bi-info-circle me-1"></i> {{ $resEst['mensaje'] }}
                                @if (isset($resEst['desactivados']) && $resEst['desactivados'] > 0)
                                    <span class="d-block text-muted">Inactivos removidos: {{ $resEst['desactivados'] }}</span>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('admin.sincronizar.estudiantes') }}" method="POST" onsubmit="return confirm('¿Confirmar sincronización de estudiantes con DUX?');">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold shadow-sm" {{ !$duxStatus['connected'] ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-repeat me-1"></i> Sincronizar Estudiantes con DUX
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 2. Sistema TEREPAIMA (Empleados / Personal) -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success shadow-sm" style="width: 48px; height: 48px; font-size: 22px;">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Sistema TEREPAIMA (RRHH)</h5>
                                <small class="text-muted">Padrón de Empleados, Docentes y Obreros</small>
                            </div>
                        </div>
                        <div>
                            @if ($terepaimaStatus['connected'])
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Conectado
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1.5 fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Desconectado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="p-3 rounded-4 mb-3 border bg-light">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted d-block mb-1">Servidor / Host:</span>
                                    <div class="fw-bold font-monospace text-dark">{{ $terepaimaStatus['host'] ?? '127.0.0.1' }}</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block mb-1">Base de Datos:</span>
                                    <div class="fw-bold font-monospace text-dark">{{ $terepaimaStatus['database'] ?? 'N/D' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 text-center mb-4">
                            <div class="col-6">
                                <div class="p-3 border rounded-4 bg-white shadow-sm h-100">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Personal en Terepaima</span>
                                    <h3 class="mb-1 fw-bold text-success">{{ number_format($terepaimaStatus['activos_remoto'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">de {{ number_format($terepaimaStatus['total_remoto'], 0, ',', '.') }} totales</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded-4 bg-white shadow-sm h-100">
                                    <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Registrados en COMESIS</span>
                                    <h3 class="mb-1 fw-bold text-primary">{{ number_format($localStats['empleados_activos'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">locales activos</small>
                                </div>
                            </div>
                        </div>

                        @if (session('sync_empleados_result'))
                            @php $resEmp = session('sync_empleados_result'); @endphp
                            <div class="alert alert-{{ $resEmp['success'] ? 'success' : 'danger' }} rounded-3 py-2 px-3 small mb-3 border-0">
                                <i class="bi bi-info-circle me-1"></i> {{ $resEmp['mensaje'] }}
                                @if (isset($resEmp['desactivados']) && $resEmp['desactivados'] > 0)
                                    <span class="d-block text-muted">Inactivos removidos: {{ $resEmp['desactivados'] }}</span>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('admin.sincronizar.empleados') }}" method="POST" onsubmit="return confirm('¿Confirmar sincronización de empleados con TEREPAIMA?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill fw-semibold shadow-sm text-white" {{ !$terepaimaStatus['connected'] ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-repeat me-1"></i> Sincronizar Empleados con TEREPAIMA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tareas Programadas Automáticas (Schedule) -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-0 pt-3 pb-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 36px; height: 36px; font-size: 18px;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">Programación Automática del Servidor (Cron Jobs)</h6>
                        <small class="text-muted">Ejecución periódica programada por el sistema</small>
                    </div>
                </div>
                <span class="badge bg-light text-muted border rounded-pill px-3 py-1.5">Frecuencia Automatizada</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Proceso / Tarea</th>
                                <th class="py-3">Origen</th>
                                <th class="py-3">Frecuencia</th>
                                <th class="py-3 text-end pe-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">Sincronización de Empleados</div>
                                    <small class="text-muted font-monospace">Job: ProcessSyncEmpleados</small>
                                </td>
                                <td class="py-3"><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1">TEREPAIMA</span></td>
                                <td class="py-3 text-secondary">Diariamente a las <strong class="text-dark">14:45 (2:45 PM)</strong></td>
                                <td class="py-3 text-end pe-4"><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Activo</span></td>
                            </tr>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">Sincronización de Estudiantes</div>
                                    <small class="text-muted font-monospace">Job: ProcessSyncEstudiantes</small>
                                </td>
                                <td class="py-3"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2.5 py-1">DUX</span></td>
                                <td class="py-3 text-secondary">Diariamente a las <strong class="text-dark">15:45 (3:45 PM)</strong></td>
                                <td class="py-3 text-end pe-4"><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Activo</span></td>
                            </tr>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">Respaldo Automático de Base de Datos</div>
                                    <small class="text-muted font-monospace">Command: db:backup</small>
                                </td>
                                <td class="py-3"><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2.5 py-1">COMESIS BD</span></td>
                                <td class="py-3 text-secondary">Trimestral <strong class="text-dark">(Cada 3 meses)</strong></td>
                                <td class="py-3 text-end pe-4"><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5 fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Activo</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

