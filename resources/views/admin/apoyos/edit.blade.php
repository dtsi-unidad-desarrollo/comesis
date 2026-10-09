@extends('layouts.app')

@section('title', 'Editar Apoyo Institucional')

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
                    <strong>¡Atención!</strong> Por favor verifica los campos con error:
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
        {{-- Banner de Encabezado --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a47a3 100%); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase" style="letter-spacing: .5px;">
                                    <i class="bi bi-pencil-square me-1"></i> Modificar Convenio #{{ $apoyo->id }}
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">{{ $apoyo->institucion }}</h2>
                                <p class="mb-0 text-white-50">Edición de datos de convenio, cupos habilitados y estado de validez</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.apoyos.ticket', $apoyo->id) }}" target="_blank"
                                   class="btn btn-light bg-opacity-25 text-white border-0 rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
                                    <i class="bi bi-printer me-2"></i>
                                    <span>Tickets QR</span>
                                </a>
                                <a href="{{ route('admin.apoyos.index') }}" 
                                   class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    <span>Volver</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de Edición --}}
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.apoyos.update', $apoyo->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            {{-- Tarjeta de estado de consumo actual --}}
                            <div class="alert alert-info border-0 rounded-3 mb-4 p-3 d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white text-info p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                                    <i class="bi bi-info-circle-fill fs-4"></i>
                                </div>
                                <div class="small">
                                    <strong class="text-dark">Estado de consumo actual:</strong> Este convenio ha consumido 
                                    <span class="badge bg-warning-subtle text-warning-emphasis font-monospace px-2 py-1 fs-7">
                                        {{ number_format($apoyo->cantidad_consumida) }} comidas
                                    </span> 
                                    de las <strong>{{ number_format($apoyo->cantidad_habilitada) }}</strong> habilitadas inicialmente.
                                </div>
                            </div>

                            <div class="row g-4">
                                {{-- Institución --}}
                                <div class="col-md-12">
                                    <label for="institucion" class="form-label fw-semibold text-secondary small mb-1">
                                        <i class="bi bi-building text-primary me-1"></i> Institución o Convenio Externo
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle">
                                            <i class="bi bi-building"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-secondary-subtle @error('institucion') is-invalid @enderror" 
                                               id="institucion" 
                                               name="institucion" 
                                               value="{{ old('institucion', $apoyo->institucion) }}" 
                                               required 
                                               placeholder="Nombre de la institución">
                                    </div>
                                    @error('institucion')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Código QR --}}
                                <div class="col-md-6">
                                    <label for="codigo" class="form-label fw-semibold text-secondary small mb-1">
                                        <i class="bi bi-qr-code text-primary me-1"></i> Código QR (Para escaneo en taquilla)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle">
                                            <i class="bi bi-upc"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-secondary-subtle font-monospace @error('codigo') is-invalid @enderror" 
                                               id="codigo" 
                                               name="codigo" 
                                               value="{{ old('codigo', $apoyo->codigo) }}" 
                                               required>
                                    </div>
                                    <small class="text-muted d-block mt-1">Si modificas el código, los tickets anteriores quedarán inválidos.</small>
                                    @error('codigo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Cantidad Habilitada --}}
                                <div class="col-md-6">
                                    <label for="cantidad_habilitada" class="form-label fw-semibold text-secondary small mb-1">
                                        <i class="bi bi-box-seam text-primary me-1"></i> Cantidad de Comidas Habilitadas
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-secondary-subtle">
                                            <i class="bi bi-123"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control border-secondary-subtle @error('cantidad_habilitada') is-invalid @enderror" 
                                               id="cantidad_habilitada" 
                                               name="cantidad_habilitada" 
                                               value="{{ old('cantidad_habilitada', $apoyo->cantidad_habilitada) }}" 
                                               required 
                                               min="{{ max(1, $apoyo->cantidad_consumida) }}">
                                    </div>
                                    <small class="text-muted d-block mt-1">Mínimo sugerido: {{ $apoyo->cantidad_consumida }} (ya consumidas).</small>
                                    @error('cantidad_habilitada')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Estatus --}}
                                <div class="col-md-12">
                                    <label for="estatus" class="form-label fw-semibold text-secondary small mb-1">
                                        <i class="bi bi-toggle-on text-primary me-1"></i> Estatus
                                    </label>
                                    <select class="form-select border-secondary-subtle @error('estatus') is-invalid @enderror" id="estatus" name="estatus" required>
                                        <option value="1" {{ old('estatus', $apoyo->estatus) == '1' ? 'selected' : '' }}>ACTIVO (Permitir acceso en recepción)</option>
                                        <option value="0" {{ old('estatus', $apoyo->estatus) == '0' ? 'selected' : '' }}>INACTIVO (Bloquear temporalmente)</option>
                                    </select>
                                    @error('estatus')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- Botones de Acción --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.apoyos.index') }}" class="btn btn-light rounded-pill px-4">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                    <i class="bi bi-check-circle-fill me-2"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
