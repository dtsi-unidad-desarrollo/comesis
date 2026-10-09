@extends('layouts.app')

@section('title', 'Padrón de Comensales')

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
                    <strong>¡Atención!</strong> Se encontraron errores en los datos enviados:
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

    {{-- Errores de importación masiva --}}
    @if(session('import_errores'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-2 text-warning"></i>
                <div class="w-100">
                    <h6 class="alert-heading fw-bold mb-1">Observaciones durante la importación de Excel</h6>
                    <p class="small text-muted mb-2">Algunos registros no pudieron ser procesados debido a inconsistencias:</p>
                    <div class="bg-white p-2 rounded border" style="max-height: 150px; overflow-y: auto;">
                        <ul class="mb-0 ps-3 small">
                            @foreach(session('import_errores') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        {{-- Banner Institucional Superior --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-people-fill me-1"></i> Padrón de Usuarios
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Lista de Comensales</h2>
                                <p class="mb-0 text-white-50">Directorio oficial de estudiantes, personal universitario y usuarios autorizados</p>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                {{-- Botón de registro y su modal --}}
                                @include('admin.comensales.partials.modalFormulario')

                                {{-- Menú de acciones Excel --}}
                                <div class="dropdown">
                                    <button class="btn btn-outline-light rounded-pill px-3 py-2 dropdown-toggle fw-semibold shadow-sm d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-file-earmark-spreadsheet me-2"></i> Herramientas Excel
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1">
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('admin.comensales.export') }}">
                                                <i class="bi bi-download text-primary me-2"></i> Exportar Registrados (.xlsx)
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item py-2" type="button" data-bs-toggle="modal" data-bs-target="#modalImportarExcel">
                                                <i class="bi bi-upload text-success me-2"></i> Importar desde Excel
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item py-2 text-muted" href="{{ route('admin.comensales.template.xlsx') }}">
                                                <i class="bi bi-file-earmark-arrow-down me-2"></i> Descargar Plantilla (.xlsx)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
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
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Total Padrón</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['total'] ?? 0) }}</h3>
                            <small class="text-primary fw-semibold"><i class="bi bi-person-lines-fill me-1"></i>Registrados</small>
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
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Estudiantes</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['estudiantes'] ?? 0) }}</h3>
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
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Personal / Empleados</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['empleados'] ?? 0) }}</h3>
                            <small class="text-success fw-semibold">Docentes, admin. y obreros</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-warning">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-check2-circle fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: .5px;">Comensales Activos</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['activos'] ?? 0) }}</h3>
                            <small class="text-warning fw-semibold">{{ number_format($stats['inactivos'] ?? 0) }} inactivos</small>
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
                        <form action="{{ route('admin.comensales.index') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="filtro" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-search text-primary me-1"></i> Búsqueda Rápida
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-secondary-subtle">
                                        <i class="bi bi-person text-secondary"></i>
                                    </span>
                                    <input type="text" class="form-control border-secondary-subtle" name="filtro" id="filtro"
                                        placeholder="Buscar por cédula, nombres o apellidos..."
                                        value="{{ $request->filtro ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="tipo" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-diagram-3 text-primary me-1"></i> Tipo de Comensal
                                </label>
                                <select name="tipo" id="tipo" class="form-select border-secondary-subtle">
                                    <option value="TODOS">Todos los tipos</option>
                                    <option value="ESTUDIANTE" {{ $request->tipo == 'ESTUDIANTE' ? 'selected' : '' }}>ESTUDIANTE</option>
                                    <option value="ESTUDIANTE FORANEO" {{ $request->tipo == 'ESTUDIANTE FORANEO' ? 'selected' : '' }}>ESTUDIANTE FORANEO</option>
                                    <option value="EMPLEADO" {{ $request->tipo == 'EMPLEADO' ? 'selected' : '' }}>EMPLEADO</option>
                                    <option value="PROFESOR" {{ $request->tipo == 'PROFESOR' ? 'selected' : '' }}>PROFESOR</option>
                                    <option value="ADMINISTRATIVO" {{ $request->tipo == 'ADMINISTRATIVO' ? 'selected' : '' }}>ADMINISTRATIVO</option>
                                    <option value="OBRERO" {{ $request->tipo == 'OBRERO' ? 'selected' : '' }}>OBRERO</option>
                                    <option value="EVENTUAL" {{ $request->tipo == 'EVENTUAL' ? 'selected' : '' }}>EVENTUAL</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="estatus" class="form-label small fw-semibold text-secondary mb-1">
                                    <i class="bi bi-toggle-on text-primary me-1"></i> Estatus
                                </label>
                                <select name="estatus" id="estatus" class="form-select border-secondary-subtle">
                                    <option value="TODOS">Todos</option>
                                    <option value="1" {{ $request->estatus === '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ $request->estatus === '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center shadow-sm" type="submit" title="Filtrar comensales">
                                    <i class="bi bi-search me-1"></i> Filtrar
                                </button>
                                @if($request->filled('filtro') || ($request->filled('tipo') && $request->tipo !== 'TODOS') || ($request->filled('estatus') && $request->estatus !== 'TODOS'))
                                    <a href="{{ route('admin.comensales.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="Limpiar Filtros">
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
                                {{ $comensales->total() }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Comensales Registrados</h5>
                            @if($request->filled('filtro') || ($request->filled('tipo') && $request->tipo !== 'TODOS') || ($request->filled('estatus') && $request->estatus !== 'TODOS'))
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-2">
                                    <i class="bi bi-funnel-fill me-1"></i> Filtro activo
                                </span>
                            @endif
                        </div>
                        <div>
                            @if($request->filled('filtro') || ($request->filled('tipo') && $request->tipo !== 'TODOS') || ($request->filled('estatus') && $request->estatus !== 'TODOS'))
                                <a href="{{ route('admin.comensales.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-arrow-repeat me-1"></i> Ver todos
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
                                    <th scope="col">Género</th>
                                    <th scope="col">Tipo de Comensal</th>
                                    <th scope="col">Estatus</th>
                                    <th scope="col" class="text-center pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($comensales as $comensal)
                                    <tr>
                                        {{-- Comensal con Avatar --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if ($comensal->foto && file_exists(public_path($comensal->foto)))
                                                    <img src="{{ asset($comensal->foto) }}" alt="Foto" 
                                                         class="rounded-circle shadow-sm me-3 flex-shrink-0" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    @php
                                                        $inicial = strtoupper(substr(trim($comensal->nombres ?? 'C'), 0, 1));
                                                        $colorBg = str_starts_with($comensal->tipo_comensal ?? '', 'ESTUDIANTE') ? '#0d6efd' : '#198754';
                                                    @endphp
                                                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                                         style="width: 40px; height: 40px; background-color: {{ $colorBg }}; font-size: 14px;">
                                                        {{ $inicial }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">
                                                        {{ $comensal->nombres }} {{ $comensal->apellidos }}
                                                    </div>
                                                    <small class="text-muted">
                                                        ID #{{ $comensal->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Cédula --}}
                                        <td>
                                            <span class="badge bg-secondary-subtle text-primary font-monospace px-2 py-1 fs-7 border">
                                                {{ $comensal->nacionalidad }}-{{ number_format($comensal->cedula, 0, '', '.') }}
                                            </span>
                                        </td>

                                        {{-- Género --}}
                                        <td>
                                            @if($comensal->sexo == 'F')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                                    <i class="bi bi-gender-female me-1"></i> Femenino
                                                </span>
                                            @elseif($comensal->sexo == 'M')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    <i class="bi bi-gender-male me-1"></i> Masculino
                                                </span>
                                            @else
                                                <span class="badge bg-light text-secondary border px-2 py-1">
                                                    N/A
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Tipo de Comensal --}}
                                        <td>
                                            @php
                                                $tipoUpper = strtoupper($comensal->tipo_comensal ?? '');
                                                $badgeClass = match(true) {
                                                    str_contains($tipoUpper, 'ESTUDIANTE') => 'bg-primary-subtle text-primary border border-primary-subtle',
                                                    str_contains($tipoUpper, 'PROFESOR') => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                                                    str_contains($tipoUpper, 'ADMINISTRATIVO') => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                                    str_contains($tipoUpper, 'OBRERO') => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                                    str_contains($tipoUpper, 'EMPLEADO') => 'bg-success-subtle text-success border border-success-subtle',
                                                    default => 'bg-light text-dark border'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} px-2 py-1">
                                                {{ $comensal->tipo_comensal ?? 'NO DEFINIDO' }}
                                            </span>
                                        </td>

                                        {{-- Estatus --}}
                                        <td id="estatus-{{ $comensal->id }}">
                                            @if ($comensal->estatus)
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
                                                {{-- Botón modal de info del estudiante --}}
                                                @include('admin.comensales.partials.modaldialog') 
                                                
                                                {{-- Botón editar --}}
                                                @include('admin.comensales.partials.modalFormularioEditar') 

                                                {{-- Botón eliminar / desactivar --}}
                                                @include('admin.comensales.partials.modal')
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="bi bi-people text-muted opacity-50" style="font-size: 3.5rem;"></i>
                                                <h5 class="fw-bold text-secondary mt-3 mb-1">No se encontraron comensales</h5>
                                                <p class="text-muted small mb-3">Intenta cambiar los parámetros del filtro o registra nuevos usuarios.</p>
                                                <a href="{{ route('admin.comensales.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
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
                                Mostrando <strong>{{ $comensales->firstItem() ?? 0 }}</strong> a <strong>{{ $comensales->lastItem() ?? 0 }}</strong> de <strong>{{ $comensales->total() }}</strong> comensales
                            </div>
                            <div>
                                {{ $comensales->appends($request->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal de Importación Masiva desde Excel --}}
    <div class="modal fade" id="modalImportarExcel" tabindex="-1" aria-labelledby="modalImportarExcelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form action="{{ route('admin.comensales.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white py-3">
                        <h5 class="modal-title fw-bold" id="modalImportarExcelLabel">
                            <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> Importación Masiva de Comensales
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 rounded-3 mb-3 small">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            Sube un archivo de hoja de cálculo con el listado de comensales. Formatos admitidos: <strong>.xlsx, .xls, .csv</strong>.
                        </div>

                        <div class="mb-3">
                            <label for="excelFile" class="form-label fw-semibold text-secondary small">Seleccionar Archivo:</label>
                            <input class="form-control" type="file" id="excelFile" name="file" accept=".xlsx,.xls,.csv" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="small text-muted">¿No tienes el formato?</span>
                            <a href="{{ route('admin.comensales.template.xlsx') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-download me-1"></i> Descargar Plantilla
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Iniciar Importación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/comensales/editar.js') }}" defer></script>
@endsection
