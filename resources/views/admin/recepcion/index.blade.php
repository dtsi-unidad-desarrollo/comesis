@extends('layouts.app')

@section('title', 'Recepción')


@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>


    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    {{-- Comensal --}}
                    @if ($comensal)
                        {{-- Tarjeta informativa --}}
                        <div class="card" style="">
                            <div class="card-header bg-success text-white">
                                Procesado
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Nombres: {{ $comensal->nombres }}</li>
                                <li class="list-group-item">Apellidos: {{ $comensal->apellidos }}</li>
                                <li class="list-group-item">Genero: {{ $comensal->sexo }}</li>
                                <li class="list-group-item">Tipo: {{ $comensal->tipo_comensal }}</li>
                                <li class="list-group-item">Sede: {{ $comensal->sede }}</li>
                                <li class="list-group-item">Dirección: {{ $comensal->direccion ?? 'N/A' }}</li>
                                <li class="list-group-item">C.I.:
                                    {{ $comensal->nacionalidad . '-' . $comensal->cedula }}
                                </li>

                                <ul class="list-group list-group-flush">
                                    @if ($comensal->carreras[0])
                                        @foreach ($comensal->carreras as $carrera)
                                            <li class="list-group-item">
                                                <strong>Carrera:</strong>
                                                {{ $carrera->codigo_carrera . ' - ' . $carrera->nombre_carrera }} <br>
                                                <strong>Programa:</strong>
                                                {{ $carrera->codigo_programa . ' - ' . $carrera->nombre_programa }} <br>
                                                <strong>Sede:</strong>
                                                {{ $carrera->codigo_sede . ' - ' . $carrera->nombre_sede }} <br>
                                                <strong>Estatus:</strong>
                                                {{ $carrera->estatus_estudiante == 'A' ? 'Activo' : 'Inactivo' }} <br>
                                            </li>
                                        @endforeach

                                    @endif
                                </ul>

                                <li class="list-group-item">
                                    <a class="btn btn-success" href="{{ route('admin.recepcion.index') }}">
                                        Continuar
                                    </a>
                                </li>

                            </ul>
                        </div>
                    @else
                        {{-- Errores --}}
                        @if ($errors->any())
                            <div class="alert alert-danger text-start">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <div id="atmSelectionSection" class="mb-4" @if($selectedAtm) style="display: none;" @endif>
                            <h5 class="card-title text-center pb-0 fs-2 text-success">Seleccione una ATM</h5>
                            @if ($atms->count())
                                <div class="row g-3">
                                    @foreach ($atms as $atm)
                                        <div class="col-md-6">
                                            <div class="card border-success shadow-sm h-100">
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title">{{ $atm->nombre }}</h5>
                                                    <p class="card-text text-muted mb-3">
                                                        {{ optional($atm->torniquete)->nombre ? 'Torniquete: ' . optional($atm->torniquete)->nombre : 'Sin torniquete asignado' }}
                                                    </p>
                                                    <form action="{{ route('admin.recepcion.index') }}" method="get" class="mt-auto">
                                                        <input type="hidden" name="atm_id" value="{{ $atm->id }}">
                                                        <button type="submit" class="btn btn-success w-100" @if (!optional($atm->torniquete)->id) disabled @endif>
                                                            Seleccionar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">No hay ATM disponibles para recepción.</div>
                            @endif
                        </div>

                        <div id="recepcionFormSection" @if (!$selectedAtm) style="display: none;" @endif>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title text-center pb-0 fs-2">Formulario de recepción</h5>
                                    <p id="selectedAtmLabel" class="text-success small mb-0">
                                        @if ($selectedAtm)
                                            {{ $selectedAtm->nombre }}
                                            @if(optional($selectedAtm->torniquete)->nombre)
                                                - {{ optional($selectedAtm->torniquete)->nombre }}
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                @if ($selectedAtm)
                                    <form action="{{ route('admin.recepcion.index') }}" method="get">
                                        <button type="submit" name="clear_atm" value="1" class="btn btn-outline-success btn-sm">
                                            Cambiar ATM
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <form action="{{ route('admin.recepcion.index') }}" class="row g-3 needs-validation mb-3"
                                method="get" novalidate>

                                <input type="hidden" name="atm_id" id="atm_id"
                                value="{{ old('atm_id', $request->atm_id ?? ($selectedAtm->id ?? '')) }}">

                            <label for="validationCustomUsername" class="form-label">Ingrese Cédula</label>

                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">
                                    <i class="bi bi-credit-card"></i>
                                </span>

                                <input type="text" class="form-control" name="cedula" autofocus id="cedula"
                                    aria-describedby="inputGroupPrepend" placeholder="Ingrese número de identificación."
                                    min="6" max="9" {{ $servicio == false ? 'readonly disabled' : '' }}
                                    required>

                                <button class="input-group-text btn btn-primary" type="submit"
                                    {{ $servicio == false ? 'disabled' : '' }} id="buscarEstudiante">Buscar</button>
                                <div class="invalid-feedback">
                                    Por favor ingrese número de identificación.
                                </div>
                            </div>
                        @empty(!$mensaje_comensal)
                            <br>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex justify-content-center align-items-center">
                                    <i class="bi bi-shield-slash text-danger fs-1 mx-2"></i>
                                    <p class="mt-1">
                                        {!! $mensaje_comensal !!}
                                    </p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        @endempty
                    </form>
                @endif

            </div>

            @if ($servicio != false)
                <div class="col-sm-6 ">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Servicio: <b>{{ $servicio->nombre }}</b></span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-columns fs-2"></i>
                                </div>
                                <div class="ps-3">
                                    <p class="text-primary">Bandejas disponible: </p>
                                    <h2 class="text-primary"> {{ $servicio->disponibilidad - $cantidadDeEntradas }}
                                    </h2>
                                    <span
                                        class="text-success small pt-1 fw-bold">{{ $servicio->disponibilidad }}</span>
                                    <span class="text-muted small pt-2 ps-1">Bandejas</span>
                                </div>
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people fs-2"></i>
                                </div>
                                <div class="ps-3">
                                    <p class="text-danger">Bandejas entregadas: </p>
                                    <h2 class="text-danger"> {{ $cantidadDeEntradas }} </h2>
                                    <span class="text-primary small pt-1 fw-bold">Horario:</span>
                                    <span
                                        class="text-muted small pt-2 ps-1">{{ $servicio->hora_inicio . '-' . $servicio->hora_cierre }}</span>

                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i
                                        class="bi {{ $mensaje_torniquete == 'Cerrado' ? 'bi-door-closed' : 'bi-door-open' }} fs-2"></i>
                                </div>
                                <div class="ps-3">
                                    <p class="text-primary">Estatus torniquete:</p>
                                    <h2
                                        class="text-secondary fs-6 {{ $mensaje_torniquete == 'Cerrado' ? 'text-danger' : 'text-success' }}  ">
                                        {{ $mensaje_torniquete }}
                                    </h2>
                                </div>

                            </div>

                        </div>
                    </div>
                @else
                    <div class="col-sm-6 col-xs-12">
                        <img src="{{ asset('assets/img/comedor-close.jpg') }}" class="" height="350px"
                            alt="img-close">
                    </div>
            @endif
        </div>
    </div>
</section>

