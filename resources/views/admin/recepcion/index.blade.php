@extends('layouts.app')

@section('title', 'Recepción')

@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-6">
                {{-- Comensal Procesado --}}
                @if ($comensal)
                    <div class="card mb-4 border-success shadow-sm">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-2">
                            <span class="fs-5 fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Acceso Permitido</span>
                            <a id="btnContinuarTop" class="btn btn-light btn-sm fw-bold text-success shadow-sm" href="{{ route('admin.recepcion.index') }}">
                                <i class="bi bi-arrow-repeat me-1"></i> Continuar (Enter ↵)
                            </a>
                        </div>
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-4 col-sm-4 text-center">
                                    <img src="{{ $comensal->foto }}" alt="Foto del comensal" class="img-fluid rounded border shadow-sm" style="max-height: 130px; width: 100%; object-fit: contain; background: #f8fafc; padding: 4px;">
                                    <div class="mt-2">
                                        <span class="badge bg-primary px-2 py-1 fs-7">{{ $comensal->tipo_comensal }}</span>
                                    </div>
                                </div>
                                <div class="col-8 col-sm-8">
                                    <h5 class="card-title text-dark p-0 mb-1 fw-bold fs-5">
                                        {{ $comensal->nombres }} {{ $comensal->tipo_comensal !== 'APOYO' ? $comensal->apellidos : '' }}
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-person-badge me-1"></i>
                                        <strong>ID / C.I.:</strong> {{ $comensal->nacionalidad ? $comensal->nacionalidad . '-' : '' }}{{ $comensal->cedula }}
                                    </p>
                                    <div class="row g-1 small">
                                        <div class="col-12 mb-1">
                                            <strong>Género:</strong> {{ $comensal->sexo }}
                                        </div>
                                        <div class="col-12 mb-1">
                                            <strong>Sede:</strong> {{ $comensal->sede }}
                                        </div>
                                        <div class="col-12">
                                            <strong>Detalle:</strong> {{ $comensal->direccion ?? ($comensal->apellidos ?? 'N/A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-3">
                                <a id="btnContinuar" class="btn btn-success btn-lg fw-bold shadow-sm" href="{{ route('admin.recepcion.index') }}" autofocus>
                                    <i class="bi bi-arrow-right-circle me-1"></i> Continuar (Presione Enter ↵)
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            {{-- Errores --}}
                            @if ($errors->any())
                                <div class="alert alert-danger text-start">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Lista de ATMs disponibles para seleccionar --}}
                            @if (!$selectedAtm)
                                <div class="text-center text-primary p-2 fs-5 fw-bold" role="alert">
                                    <i class="bi bi-door-open me-1"></i> Seleccione un Torniquete / Terminal:
                                </div>
                                
                                <div class="row">
                                    @foreach ($atms as $atm)
                                    <div class="col-md-6 my-2 text-center">
                                        <a href="{{ route('admin.recepcion.selectAtm') }}?atm_id={{ $atm->id }}"
                                            class="btn btn-outline-primary w-100 p-3 shadow-sm">
                                            <img src="{{ asset('assets/img/torniquete.png') }}" alt="ATM Icon" width="100" height="100"
                                            class="mb-2 d-block mx-auto">
                                            <strong>{{ $atm->nombre }}</strong>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                {{-- Formulario de búsqueda --}}
                                <form action="{{ route('admin.recepcion.index') }}" class="row g-3 needs-validation"
                                    method="get" novalidate>

                                    <label for="cedula" class="form-label text-dark fw-bold mb-1">
                                        <i class="bi bi-upc-scan me-1"></i> Cédula de Identidad o Código de Apoyo
                                    </label>

                                    <div class="input-group has-validation input-group-lg">
                                        <span class="input-group-text bg-light" id="inputGroupPrepend">
                                            <i class="bi bi-credit-card"></i>
                                        </span>

                                        <input type="text" class="form-control" name="cedula" autofocus id="cedula"
                                            aria-describedby="inputGroupPrepend"
                                            placeholder="Escanear o ingresar código..."
                                            autocomplete="off"
                                            {{ $servicio == false ? 'readonly disabled' : '' }} required>

                                        <button class="btn btn-primary px-4" type="submit"
                                            {{ $servicio == false ? 'disabled' : '' }}
                                            id="buscarEstudiante">
                                            <i class="bi bi-search me-1"></i> Buscar
                                        </button>
                                        <div class="invalid-feedback">
                                            Por favor ingrese o escanee un número de identificación.
                                        </div>
                                    </div>

                                    @empty(!$mensaje_comensal)
                                        <div class="col-12 mt-3">
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-shield-slash-fill text-danger fs-2 me-3"></i>
                                                    <div>
                                                        {!! $mensaje_comensal !!}
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    @endempty
                                </form>
                                {{-- end formulario --}}

                                {{-- Boton para deseleccionar ATM --}}
                                @if ($selectedAtm)
                                    <div class="mt-3 text-end">
                                        <a href="{{ route('admin.recepcion.selectAtm') }}?change=true"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-circle me-1"></i> Deseleccionar {{ $selectedAtm->nombre }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            @if ($servicio != false)
                <div class="col-12 col-md-6">
                    <div class="card info-card sales-card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-dark">Servicio Actual: <span class="badge bg-primary fs-6">{{ $servicio->nombre }}</span></h5>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 border rounded bg-light text-center">
                                        <p class="text-muted mb-1 small fw-bold">BANDEJAS DISPONIBLES</p>
                                        <h2 class="text-primary mb-0 fw-bold">{{ $servicio->disponibilidad - $cantidadDeEntradas }}</h2>
                                        <small class="text-muted">de {{ $servicio->disponibilidad }} total</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 border rounded bg-light text-center">
                                        <p class="text-muted mb-1 small fw-bold">BANDEJAS ENTREGADAS</p>
                                        <h2 class="text-danger mb-0 fw-bold">{{ $cantidadDeEntradas }}</h2>
                                        <small class="text-muted">Horario: {{ $servicio->hora_inicio }} - {{ $servicio->hora_cierre }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mt-3 p-2 border rounded bg-white">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="bi {{ $mensaje_torniquete == 'Cerrado' ? 'bi-door-closed text-danger' : 'bi-door-open text-success' }} fs-2"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small fw-bold">ESTATUS TORNIQUETE</p>
                                    <span class="fs-6 {{ $mensaje_torniquete == 'Cerrado' ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                        {{ $mensaje_torniquete }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 col-md-6 text-center">
                    <img src="{{ asset('assets/img/comedor-close.jpg') }}" class="img-fluid rounded shadow-sm" style="max-height: 250px;" alt="Comedor cerrado">
                </div>
            @endif

        </div>
    </section>

    @if ($comensal)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('btnContinuar');
            if (btn) btn.focus();

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    if (window.ComesisPreloader) {
                        window.ComesisPreloader.show('Punto de Control', 'Preparando siguiente registro...');
                    }
                    window.location.href = "{{ route('admin.recepcion.index') }}";
                }
            });
        });
    </script>
    @else
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('cedula');
            if (input) input.focus();
        });
    </script>
    @endif
@endsection
