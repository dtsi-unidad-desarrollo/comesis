@extends('layouts.app')

@section('title', 'Recepción')


@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>


    <section class="section" style="height: 67vh">

        <div class="container">
            <div class="row">

                <div class="col-sm-6">
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
                                                <strong>Carrera:</strong> {{ $carrera->codigo_carrera ." - ". $carrera->nombre_carrera }} <br>
                                                <strong>Programa:</strong> {{ $carrera->codigo_programa ." - ". $carrera->nombre_programa }} <br>
                                                <strong>Sede:</strong> {{ $carrera->codigo_sede ." - ". $carrera->nombre_sede }} <br>
                                                <strong>Estatus:</strong> {{ $carrera->estatus_estudiante == "A" ? "Activo" : "Inactivo" }} <br>
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
                        @if ($errors->any())
                            <div class="alert alert-danger text-start">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        {{-- filtro de comensales --}}

                        <div class=" pb-2">
                            <h5 class="card-title text-center pb-0 fs-2">Formulario de recepción</h5>
                            <p class="text-center text-danger small">Ingrese el número de cédula del comensal.</p>
                        </div>
                        <form action="{{ route('admin.recepcion.index') }}" class="row g-3 needs-validation mb-3" method="post"
                            novalidate>
                            @csrf
                            @method('get')

                            <label for="validationCustomUsername" class="form-label">Ingrese Cédula</label>

                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">
                                    <i class="bi bi-credit-card"></i>
                                </span>
                                <input type="text" class="form-control" name="cedula" autofocus id="cedula"
                                    aria-describedby="inputGroupPrepend" placeholder="Ingrese número de identificación."
                                    min="6" max="9" {{ $servicio == false ? "readonly disabled" : "" }} required>
                                <button class="input-group-text btn btn-primary" type="submit" 
                                    {{ $servicio == false ? "disabled" : "" }} 
                                    id="buscarEstudiante">Buscar</button>
                                <div class="invalid-feedback">
                                    Por favor ingrese número de identificación.
                                </div>
                            </div>
                        @empty(!$mensaje_comensal)
                            <br>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                 {!! $mensaje_comensal !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                        <h2 class="text-primary"> {{ $servicio->disponibilidad - $cantidadDeEntradas }} </h2>
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
                                        <span class="text-muted small pt-2 ps-1">{{ $servicio->hora_inicio ."-". $servicio->hora_cierre}}</span>

                                    </div>
                                </div>
                            
                            </div>

                        </div>
                    </div>
                @else
                    <div class="col-sm-6 col-xs-12">
                        <img src="{{ asset('assets/img/comedor-close.jpg')}}" class="" height="350px" alt="img-close">
                    </div>
                @endif
            </div>
        </div>
</section>



@endsection
