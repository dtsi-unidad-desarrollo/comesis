@extends('layouts.app')

@section('title', 'Recepción')


@section('content')

    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>


    <section class="section">
        <div class="row">
            <div class="col-12 col-sm-6">
                {{-- Comensal --}}
                @if ($comensal)
                    {{-- Tarjeta informativa --}}
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            Procesado
                        </div>
                        <ul class="list-group list-group-flush">
                            {{-- Foto del comensal --}}
                            <li class="list-group-item">
                                <img src="{{ $comensal->foto }}" alt="Foto del comensal" class="img-fluid">
                            </li>
                            <li class="list-group-item">Nombres: {{ $comensal->nombres }}</li>
                            <li class="list-group-item">Apellidos: {{ $comensal->apellidos }}</li>
                            <li class="list-group-item">Genero: {{ $comensal->sexo }}</li>
                            <li class="list-group-item">Tipo: {{ $comensal->tipo_comensal }}</li>
                            <li class="list-group-item">Sede: {{ $comensal->sede }}</li>
                            <li class="list-group-item">Dirección: {{ $comensal->direccion ?? 'N/A' }}</li>
                            <li class="list-group-item">C.I.:
                                {{ $comensal->nacionalidad . '-' . $comensal->cedula }}
                            </li>

                            <li class="list-group-item">
                                <a class="btn btn-success" href="{{ route('admin.recepcion.index') }}">
                                    Continuar
                                </a>
                            </li>

                        </ul>
                    </div>
                @else
                    <div class="card mb-4">
                        <div class="card-body">
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

                            {{-- lista de ATMs disponibles para seleccionar --}}
                            @if (!$selectedAtm)
                                <div class="text-center text-primary p-2 fs-4" role="alert">
                                    <span class="ms-2">Seleccione un ATM/Torniquete disponible:</span>
                                </div>
                                
                                <div class="row">
                                    @foreach ($atms as $atm)
                                    <div class="col-md-6 my-3 text-center">
                                        <a href="{{ route('admin.recepcion.selectAtm') }}?atm_id={{ $atm->id }}"
                                            class="btn btn-outline-primary">
                                            <img src="{{ asset('assets/img/torniquete.png') }}" alt="ATM Icon" width="150" height="150"
                                            class="me-2">
                                            {{ $atm->nombre }}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                {{-- Formulario de búsqueda --}}
                                <form action="{{ route('admin.recepcion.index') }}" class="row g-3 needs-validation"
                                    method="get" novalidate>

                                    <label for="validationCustomUsername" class="form-label text-danger p-2">Ingrese
                                        Cédula</label>

                                        <div class="input-group has-validation">
                                            <span class="input-group-text" id="inputGroupPrepend">
                                                <i class="bi bi-credit-card"></i>
                                            </span>

                                            <input type="text" class="form-control" name="cedula" autofocus id="cedula"
                                                aria-describedby="inputGroupPrepend"
                                                placeholder="Ingrese número de identificación." min="6" max="9"
                                                {{ $servicio == false ? 'readonly disabled' : '' }} required>

                                            <button class="input-group-text btn btn-primary" type="submit"
                                                {{ $servicio == false ? 'disabled' : '' }}
                                                id="buscarEstudiante">Buscar</button>
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
                                {{-- end formulario --}}

                                {{-- Boton para deseleccionar ATM --}}
                                @if ($selectedAtm)
                                    <a href="{{ route('admin.recepcion.selectAtm') }}?change=true"
                                        class="btn btn-outline-danger mt-3">
                                        <i class="bi bi-x-circle"></i> Deseleccionar {{ $selectedAtm->nombre }}

                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        @if ($servicio != false)
            <div class="col-12 col-sm-6">
                <div class="card info-card sales-card h-100">
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
                                <span class="text-success small pt-1 fw-bold">{{ $servicio->disponibilidad }}</span>
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
            </div>
        @else
            <div class="col-sm-6 col-xs-12">
                <img src="{{ asset('assets/img/comedor-close.jpg') }}" class="" height="350px" alt="img-close">
            </div>
        @endif

    </div>
</section>
@endsection
