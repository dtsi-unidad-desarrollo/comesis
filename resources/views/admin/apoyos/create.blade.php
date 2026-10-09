@extends('layouts.app')

@section('title', 'Registrar Apoyo Institucional')

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
                                    <i class="bi bi-plus-circle me-1"></i> Nuevo Registro
                                </span>
                                <h2 class="h3 fw-bold mb-1 text-white">Registrar Apoyo Institucional</h2>
                                <p class="mb-0 text-white-50">Crea un convenio institucional con cupos autorizados y código QR para taquilla</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.apoyos.index') }}" 
                                   class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    <span>Volver a la Lista</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de Creación --}}
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.apoyos.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

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
                                               value="{{ old('institucion') }}" 
                                               required 
                                               placeholder="Ej. GOBERNACIÓN DE BARINAS, ZAMORA FC SUB 16...">
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
                                               value="{{ old('codigo') }}" 
                                               required 
                                               placeholder="Ej. APOYO-XXXXXX">
                                        <button class="btn btn-outline-primary" type="button" onclick="generateCode()" title="Generar código aleatorio">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Generar
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-1">Este código se codificará en los tickets imprimibles.</small>
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
                                               value="{{ old('cantidad_habilitada', 10) }}" 
                                               required 
                                               min="1">
                                    </div>
                                    <small class="text-muted d-block mt-1">Límite máximo de bandejas que podrán retirar.</small>
                                    @error('cantidad_habilitada')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Estatus --}}
                                <div class="col-md-12">
                                    <label for="estatus" class="form-label fw-semibold text-secondary small mb-1">
                                        <i class="bi bi-toggle-on text-primary me-1"></i> Estatus Inicial
                                    </label>
                                    <select class="form-select border-secondary-subtle @error('estatus') is-invalid @enderror" id="estatus" name="estatus" required>
                                        <option value="1" {{ old('estatus', '1') == '1' ? 'selected' : '' }}>ACTIVO (Permitir acceso en recepción)</option>
                                        <option value="0" {{ old('estatus') == '0' ? 'selected' : '' }}>INACTIVO (Bloquear temporalmente)</option>
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
                                    <i class="bi bi-check-circle-fill me-2"></i> Guardar Convenio
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function generateCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = 'APOYO-';
        for (let i = 0; i < 6; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('codigo').value = result;
    }

    // Generar código automático si el campo está vacío al cargar
    document.addEventListener('DOMContentLoaded', function() {
        const codigoInput = document.getElementById('codigo');
        if (codigoInput && !codigoInput.value) {
            generateCode();
        }
    });
    </script>
@endsection
