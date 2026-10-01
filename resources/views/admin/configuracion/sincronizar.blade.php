@extends('layouts.app')

@section('title', 'Sincronización de Datos - Dux y Terepaima')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>

    <section class="section">
        <!-- Banner de Encabezado -->
        <div class="card border-0 shadow-sm text-white mb-4" style="background: linear-gradient(135deg, #132743 0%, #1f4068 100%); border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-arrow-repeat fs-3 text-warning"></i>
                            <h4 class="mb-0 fw-bold text-white">Módulo de Sincronización de Datos</h4>
                        </div>
                        <p class="mb-0 text-white-50 small">
                            Actualice el padrón local de comensales desde los sistemas centrales universitarios: <strong>DUX</strong> (Estudiantes) y <strong>TEREPAIMA</strong> (Empleados/Personal).
                        </p>
                    </div>
                    <div>
                        <form action="{{ route('admin.sincronizarData') }}" method="POST" onsubmit="return confirm('¿Desea iniciar la sincronización completa de Estudiantes y Empleados? Este proceso actualizará la base de datos local.');">
                            @csrf
                            <input type="hidden" name="tipo" value="todos">
                            <button type="submit" class="btn btn-warning fw-bold px-4 py-2 shadow-sm text-dark">
                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Sincronizar Todo Ahora
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- 1. Sistema DUX (Estudiantes) -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 44px; height: 44px; font-size: 22px;">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Sistema DUX (SISE / ARSE)</h5>
                                <small class="text-muted">Padrón de Estudiantes Universitarios</small>
                            </div>
                        </div>
                        <div>
                            @if ($duxStatus['connected'])
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Conectado
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Desconectado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="bg-light p-3 rounded mb-3 border">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted">Servidor / Host:</span>
                                    <div class="fw-bold font-monospace">{{ $duxStatus['host'] ?? '127.0.0.1' }}</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Base de Datos:</span>
                                    <div class="fw-bold font-monospace">{{ $duxStatus['database'] ?? 'N/D' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 text-center mb-4">
                            <div class="col-6">
                                <div class="p-3 border rounded bg-white">
                                    <span class="text-muted small text-uppercase">Estudiantes Activos en DUX</span>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format($duxStatus['activos_remoto'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">de {{ number_format($duxStatus['total_remoto'], 0, ',', '.') }} totales</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-white">
                                    <span class="text-muted small text-uppercase">Registrados en COMESIS</span>
                                    <h3 class="mb-0 fw-bold text-success">{{ number_format($localStats['estudiantes_activos'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">estudiantes locales activos</small>
                                </div>
                            </div>
                        </div>

                        @if (session('sync_estudiantes_result'))
                            @php $resEst = session('sync_estudiantes_result'); @endphp
                            <div class="alert alert-{{ $resEst['success'] ? 'success' : 'danger' }} py-2 px-3 small mb-3">
                                <i class="bi bi-info-circle me-1"></i> {{ $resEst['mensaje'] }}
                                @if (isset($resEst['desactivados']) && $resEst['desactivados'] > 0)
                                    <span class="d-block text-muted">Inactivos removidos: {{ $resEst['desactivados'] }}</span>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('admin.sincronizar.estudiantes') }}" method="POST" onsubmit="return confirm('¿Confirmar sincronización de estudiantes con DUX?');">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" {{ !$duxStatus['connected'] ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-repeat me-1"></i> Sincronizar Estudiantes con DUX
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 2. Sistema TEREPAIMA (Empleados / Personal) -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 44px; height: 44px; font-size: 22px;">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Sistema TEREPAIMA (RRHH)</h5>
                                <small class="text-muted">Padrón de Empleados, Docentes y Obreros</small>
                            </div>
                        </div>
                        <div>
                            @if ($terepaimaStatus['connected'])
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Conectado
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Desconectado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="bg-light p-3 rounded mb-3 border">
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span class="text-muted">Servidor / Host:</span>
                                    <div class="fw-bold font-monospace">{{ $terepaimaStatus['host'] ?? '127.0.0.1' }}</div>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted">Base de Datos:</span>
                                    <div class="fw-bold font-monospace">{{ $terepaimaStatus['database'] ?? 'N/D' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 text-center mb-4">
                            <div class="col-6">
                                <div class="p-3 border rounded bg-white">
                                    <span class="text-muted small text-uppercase">Personal Activo en Terepaima</span>
                                    <h3 class="mb-0 fw-bold text-success">{{ number_format($terepaimaStatus['activos_remoto'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">de {{ number_format($terepaimaStatus['total_remoto'], 0, ',', '.') }} totales</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-white">
                                    <span class="text-muted small text-uppercase">Registrados en COMESIS</span>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format($localStats['empleados_activos'], 0, ',', '.') }}</h3>
                                    <small class="text-muted">empleados locales activos</small>
                                </div>
                            </div>
                        </div>

                        @if (session('sync_empleados_result'))
                            @php $resEmp = session('sync_empleados_result'); @endphp
                            <div class="alert alert-{{ $resEmp['success'] ? 'success' : 'danger' }} py-2 px-3 small mb-3">
                                <i class="bi bi-info-circle me-1"></i> {{ $resEmp['mensaje'] }}
                                @if (isset($resEmp['desactivados']) && $resEmp['desactivados'] > 0)
                                    <span class="d-block text-muted">Inactivos removidos: {{ $resEmp['desactivados'] }}</span>
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('admin.sincronizar.empleados') }}" method="POST" onsubmit="return confirm('¿Confirmar sincronización de empleados con TEREPAIMA?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm" {{ !$terepaimaStatus['connected'] ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-repeat me-1"></i> Sincronizar Empleados con TEREPAIMA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tareas Programadas Automáticas (Schedule) -->
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-clock-history text-primary me-1"></i> Programación Automática del Servidor (Cron Jobs)
                </h6>
                <span class="badge bg-light text-muted border">Frecuencia Diaria / Periódica</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th class="ps-4">Proceso / Tarea</th>
                                <th>Origen</th>
                                <th>Frecuencia</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">Sincronización de Empleados</div>
                                    <small class="text-muted">Job: ProcessSyncEmpleados</small>
                                </td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border">TEREPAIMA</span></td>
                                <td>Diariamente a las <strong>14:45 (2:45 PM)</strong></td>
                                <td><span class="badge bg-success"><i class="bi bi-check2"></i> Activo en Programador</span></td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">Sincronización de Estudiantes</div>
                                    <small class="text-muted">Job: ProcessSyncEstudiantes</small>
                                </td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary border">DUX</span></td>
                                <td>Diariamente a las <strong>15:45 (3:45 PM)</strong></td>
                                <td><span class="badge bg-success"><i class="bi bi-check2"></i> Activo en Programador</span></td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">Respaldo Automático de Base de Datos</div>
                                    <small class="text-muted">Command: db:backup</small>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border">COMESIS BD</span></td>
                                <td>Trimestral (Cada 3 meses)</td>
                                <td><span class="badge bg-success"><i class="bi bi-check2"></i> Activo en Programador</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
